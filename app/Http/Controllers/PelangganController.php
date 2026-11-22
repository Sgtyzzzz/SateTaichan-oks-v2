<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PelangganController extends Controller
{
    public function index()
    {
        $menus = Menu::where('is_available', true)->where('stock', '>', 0)->get();
        $tables = Table::orderBy('table_number')->get();
        $myOrders = Order::with(['items.menu', 'table'])
            ->where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        return view('pelanggan.menu', compact('menus', 'tables', 'myOrders'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'order_type' => ['required', 'in:dine_in,take_away'],
            'table_id' => ['required_if:order_type,dine_in', 'nullable', 'exists:tables,id'],
            'spicy_level' => ['required', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['required', 'exists:menus,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        return DB::transaction(function () use ($request) {
            if ($request->order_type === 'dine_in') {
                $table = Table::lockForUpdate()->findOrFail($request->table_id);
                if ($table->status !== 'available') {
                    return back()->withErrors(['table_id' => 'Maaf, meja tersebut baru saja dipesan orang lain. Silakan pilih meja lain.']);
                }
                $table->update(['status' => 'reserved']);
            }

            $subtotal = 0;
            $orderItemsData = [];

            foreach ($request->items as $item) {
                $menu = Menu::lockForUpdate()->findOrFail($item['menu_id']);
                $qty = (int) $item['quantity'];

                if ($menu->stock < $qty) {
                    throw new \Exception("Stok {$menu->name} tidak mencukupi.");
                }

                $itemSubtotal = $menu->price * $qty;
                $subtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'menu' => $menu,
                    'quantity' => $qty,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $serviceFee = 2000;
            $totalPrice = $subtotal + $serviceFee;

            $order = Order::create([
                'order_code' => 'OKS-' . strtoupper(Str::random(6)),
                'user_id' => Auth::id(),
                'customer_name' => Auth::user()->name,
                'table_id' => $request->order_type === 'dine_in' ? $request->table_id : null,
                'order_type' => $request->order_type,
                'subtotal' => $subtotal,
                'service_fee' => $serviceFee,
                'total_price' => $totalPrice,
                'payment_status' => 'unpaid',
                'order_status' => 'menunggu',
            ]);

            foreach ($orderItemsData as $data) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $data['menu']->id,
                    'quantity' => $data['quantity'],
                    'spicy_level' => $request->spicy_level,
                    'subtotal' => $data['subtotal'],
                ]);

                $data['menu']->decrement('stock', $data['quantity']);
            }

            return redirect()->route('pelanggan.payment', $order->id);
        });
    }

    public function payment(Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);
        $order->load(['items.menu', 'table']);
        return view('pelanggan.payment', compact('order'));
    }

    public function confirmPayment(Request $request, Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        $request->validate([
            'payment_method' => ['required', 'string'],
        ]);

        // Jika bayar tunai di kasir -> pending (menunggu konfirmasi kasir)
        // Jika e-wallet/VA -> simulasi pembayaran instan berhasil (pending/paid)
        $status = $request->payment_method === 'Bayar di Kasir (Tunai)' ? 'pending' : 'pending';

        $order->update([
            'payment_method' => $request->payment_method,
            'payment_status' => $status,
        ]);

        return redirect()->route('pelanggan.menu')->with('success', "Pesanan #{$order->order_code} berhasil dibuat! Menunggu konfirmasi pembayaran oleh kasir.");
    }

    public function midtransCallback(Request $request)
    {
        $order = Order::where('order_code', $request->order_id)->first();
        if ($order && in_array($request->transaction_status, ['capture', 'settlement'])) {
            $order->update(['payment_status' => 'paid', 'order_status' => 'diproses']);
            if ($order->table) {
                $order->table->update(['status' => 'occupied']);
            }
        }
        return response()->json(['status' => 'ok']);
    }
}