<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PelangganController extends Controller
{
    public function index()
    {
        $menus = Menu::where('is_available', true)->where('stock', '>', 0)->get();
        $tables = Table::where('status', 'available')->get();
        $myOrders = Order::with(['items.menu', 'table'])->where('user_id', Auth::id())->latest()->get();

        return view('pelanggan.index', compact('menus', 'tables', 'myOrders'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string|max:255',
            'table_id' => 'nullable|exists:tables,id',
        ]);

        return DB::transaction(function () use ($request) {
            // 1. Kunci meja jika Dine-In
            if ($request->table_id) {
                $table = Table::lockForUpdate()->findOrFail($request->table_id);
                if ($table->status !== 'available') {
                    return back()->withErrors(['table_id' => 'Maaf, meja baru saja dibooking pelanggan lain.']);
                }
                $table->update(['status' => 'booked']);
            }

            // 2. Hitung total & kurangi stok menu
            $totalPrice = 0;
            $orderItemsData = [];

            foreach ($request->items as $item) {
                $menu = Menu::lockForUpdate()->findOrFail($item['menu_id']);
                if ($menu->stock < $item['quantity']) {
                    throw new \Exception("Stok {$menu->name} tidak mencukupi.");
                }
                $subtotal = $menu->price * $item['quantity'];
                $totalPrice += $subtotal;

                $menu->decrement('stock', $item['quantity']);

                $orderItemsData[] = [
                    'menu_id' => $menu->id,
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                    'subtotal' => $subtotal,
                    'notes' => $item['notes'] ?? null,
                ];
            }

            // 3. Simpan Order
            $order = Order::create([
                'order_code' => 'TCN-' . now()->format('YmdHis') . rand(10, 99),
                'user_id' => Auth::id(),
                'table_id' => $request->table_id,
                'order_type' => $request->table_id ? 'dine_in' : 'takeaway',
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            $order->items()->createMany($orderItemsData);

            // 4. Generate Midtrans Snap Token (All Payment)
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id' => $order->order_code,
                    'gross_amount' => (int) $order->total_price,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone,
                ],
            ]);

            $order->update(['snap_token' => $snapToken]);

            return response()->json([
                'order_code' => $order->order_code,
                'snap_token' => $snapToken,
            ]);
        });
    }

    // Webhook Callback dari Midtrans
    public function midtransCallback(Request $request)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');

        $notif = new Notification();
        $order = Order::where('order_code', $notif->order_id)->firstOrFail();

        if (in_array($notif->transaction_status, ['capture', 'settlement'])) {
            $order->update([
                'payment_status' => 'paid',
                'payment_method' => $notif->payment_type,
            ]);
        } elseif (in_array($notif->transaction_status, ['cancel', 'deny', 'expire'])) {
            $order->update([
                'payment_status' => 'failed',
                'status' => 'cancelled',
            ]);
            if ($order->table_id) {
                $order->table->update(['status' => 'available']);
            }
        }

        return response()->json(['message' => 'OK']);
    }
}