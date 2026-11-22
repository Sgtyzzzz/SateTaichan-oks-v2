<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalOmzet = Order::where('payment_status', 'paid')->sum('total_price');
        $pesananSelesai = Order::where('status', 'completed')->count();
        $pesananBelumSelesai = Order::whereIn('status', ['pending', 'confirmed', 'cooking'])
            ->where('payment_status', 'paid')
            ->count();

        $orders = Order::with(['user', 'kasir', 'table'])->latest()->get();
        $menus = Menu::latest()->get();
        $tables = Table::orderBy('table_number')->get();

        return view('admin.dashboard', compact(
            'totalOmzet', 'pesananSelesai', 'pesananBelumSelesai', 'orders', 'menus', 'tables'
        ));
    }

    // --- CRUD MENU ---
    public function storeMenu(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|in:sate,pelengkap,minuman',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        Menu::create($data);
        return back()->with('success', 'Menu berhasil ditambahkan.');
    }

    public function updateMenu(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|in:sate,pelengkap,minuman',
            'stock' => 'required|integer|min:0',
            'is_available' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($menu->image) Storage::disk('public')->delete($menu->image);
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($data);
        return back()->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroyMenu(Menu $menu)
    {
        if ($menu->image) Storage::disk('public')->delete($menu->image);
        $menu->delete();
        return back()->with('success', 'Menu berhasil dihapus.');
    }

    // --- CRUD MEJA ---
    public function storeTable(Request $request)
    {
        $data = $request->validate([
            'table_number' => 'required|string|unique:tables,table_number',
            'capacity' => 'required|integer|min:1',
        ]);

        Table::create($data);
        return back()->with('success', 'Meja berhasil ditambahkan.');
    }

    public function updateTable(Request $request, Table $table)
    {
        $data = $request->validate([
            'table_number' => 'required|string|unique:tables,table_number,' . $table->id,
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,booked,occupied',
        ]);

        $table->update($data);
        return back()->with('success', 'Data meja diperbarui.');
    }

    public function destroyTable(Table $table)
    {
        $table->delete();
        return back()->with('success', 'Meja berhasil dihapus.');
    }

    // --- EXPORT EXCEL ---
    public function exportExcel()
    {
        $fileName = 'laporan-keuangan-taichan-' . now()->format('Y-m-d') . '.xlsx';
        $filePath = storage_path('app/' . $fileName);

        $writer = new Writer();
        $writer->openToFile($filePath);

        $writer->addRow(Row::fromValues([
            'Kode Order', 'Tanggal', 'Pelanggan', 'Meja', 'Kasir', 'Metode Bayar', 'Status Pesanan', 'Total Omzet'
        ]));

        $orders = Order::with(['user', 'kasir', 'table'])
            ->where('payment_status', 'paid')
            ->latest()
            ->get();

        foreach ($orders as $order) {
            $writer->addRow(Row::fromValues([
                $order->order_code,
                $order->created_at->format('d-m-Y H:i'),
                $order->user->name,
                $order->table->table_number ?? 'Takeaway',
                $order->kasir->name ?? '-',
                strtoupper($order->payment_method ?? '-'),
                strtoupper($order->status),
                (float) $order->total_price,
            ]));
        }

        $writer->close();
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}