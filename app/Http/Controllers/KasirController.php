<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KasirController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'table', 'items.menu'])
            ->latest()
            ->get();

        $countBaru = $orders->where('order_status', 'pending')->count();
        $countProses = $orders->where('order_status', 'cooking')->count();
        $countSiap = $orders->where('order_status', 'ready')->count();

        $tables = Table::orderBy('id')->get();
        $menus = Menu::where('is_available', true)->where('stock', '>', 0)->get();

        return view('kasir.dashboard', compact(
            'orders',
            'countBaru',
            'countProses',
            'countSiap',
            'tables',
            'menus'
        ));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'nullable|in:unpaid,paid,failed',
            'order_status' => 'nullable|in:pending,cooking,ready,completed,cancelled',
        ]);

        if ($request->filled('payment_status')) {
            $order->payment_status = $request->payment_status;
        }

        if ($request->filled('order_status')) {
            $order->order_status = $request->order_status;

            if (in_array($request->order_status, ['completed', 'cancelled']) && $order->table_id) {
                Table::where('id', $order->table_id)->update(['status' => 'available']);
            }
        }

        $order->save();

        return back()->with('success', "Status pesanan #{$order->order_code} berhasil diperbarui!");
    }

    public function storeOfflineOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:100',
            'order_type' => 'required|in:dine_in,take_away',
            'table_id' => 'required_if:order_type,dine_in|nullable|exists:tables,id',
            'payment_method' => 'required|in:cash,qris,transfer',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $subtotal = 0;
            $orderItemsData = [];

            foreach ($request->items as $item) {
                $menu = Menu::lockForUpdate()->findOrFail($item['menu_id']);
                if ($menu->stock < $item['quantity']) {
                    abort(422, "Stok {$menu->name} tidak mencukupi.");
                }

                $itemSubtotal = $menu->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $orderItemsData[] = [
                    'menu_id' => $menu->id,
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                    'subtotal' => $itemSubtotal,
                ];

                $menu->decrement('stock', $item['quantity']);
            }

            $customerName = $request->filled('customer_name')
                ? $request->customer_name
                : 'Pelanggan Walk-in';

            $order = Order::create([
                'order_code' => 'OFF-' . strtoupper(Str::random(4)),
                'user_id' => Auth::id(),
                'customer_name' => $customerName,
                'table_id' => $request->order_type === 'dine_in' ? $request->table_id : null,
                'order_type' => $request->order_type,
                'spicy_level' => 'Level 2 - Sedang',
                'subtotal' => $subtotal,
                'service_fee' => 0,
                'total_price' => $subtotal,
                'payment_method' => $request->payment_method,
                'payment_status' => 'paid',
                'order_status' => 'cooking',
            ]);

            foreach ($orderItemsData as $data) {
                $order->items()->create($data);
            }

            if ($request->order_type === 'dine_in' && $request->table_id) {
                Table::where('id', $request->table_id)->update(['status' => 'occupied']);
            }
        });

        return back()->with('success', 'Pesanan kasir berhasil dibuat & masuk antrean dapur!');
    }

    public function updateTableStatus(Request $request, Table $table)
    {
        $request->validate([
            'status' => 'required|in:available,occupied,reserved',
        ]);

        $table->update(['status' => $request->status]);

        return back()->with('success', "Status {$table->table_number} diperbarui.");
    }
}