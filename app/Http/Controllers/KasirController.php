<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'table', 'items.menu'])
            ->where('payment_status', 'paid')
            ->latest()
            ->get();

        return view('kasir.dashboard', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cooking,completed,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
            'kasir_id' => Auth::id(),
        ]);

        // Jika pesanan selesai & dine-in, kembalikan status meja menjadi available
        if ($request->status === 'completed' && $order->table_id) {
            $order->table->update(['status' => 'available']);
        }

        return back()->with('success', "Status pesanan {$order->order_code} diperbarui menjadi {$request->status}.");
    }
}