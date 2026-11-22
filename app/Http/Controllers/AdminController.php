<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // 1. DASHBOARD RINGKASAN PERFORMA
    public function dashboard()
    {
        $todayOrdersCount = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('total_price');
        $newCustomersCount = User::where('role', 'pelanggan')
            ->whereDate('created_at', today())
            ->count();

        // Grafik Pendapatan 7 Hari Terakhir (Sen - Min)
        $weeklyChart = collect();
        $daysIndo = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $rev = Order::where('payment_status', 'paid')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_price');
            $weeklyChart->push([
                'day' => $daysIndo[$date->dayOfWeek],
                'total' => $rev,
                'is_today' => $i === 0,
            ]);
        }
        $maxWeekly = max($weeklyChart->max('total'), 1);

        // Top 3 Menu Terlaris
        $topMenus = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('menu')
            ->groupBy('menu_id')
            ->orderByDesc('total_sold')
            ->take(3)
            ->get();

        // 5 Pesanan Terbaru
        $recentOrders = Order::with(['user', 'table', 'items.menu'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'todayOrdersCount',
            'todayRevenue',
            'newCustomersCount',
            'weeklyChart',
            'maxWeekly',
            'topMenus',
            'recentOrders'
        ));
    }

    // 2. RIWAYAT PESANAN (ONLINE & OFFLINE)
    public function pesanan(Request $request)
    {
        $totalOrders = Order::count();
        $onlineCount = Order::where('order_code', 'not like', 'OFF-%')->count();
        $offlineCount = Order::where('order_code', 'like', 'OFF-%')->count();

        $tab = $request->get('tab', 'online');
        $query = Order::with(['user', 'table', 'items.menu'])->latest();

        if ($tab === 'offline') {
            $query->where('order_code', 'like', 'OFF-%');
        } else {
            $query->where('order_code', 'not like', 'OFF-%');
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('order_code', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.pesanan', compact(
            'orders',
            'tab',
            'totalOrders',
            'onlineCount',
            'offlineCount'
        ));
    }

    // 3. PENGELOLAAN MENU MAKANAN
    public function menu(Request $request)
    {
        $totalMenus = Menu::count();
        $categoriesCount = Menu::distinct('category')->count('category');
        $lowStockCount = Menu::where('stock', '<=', 10)->count();

        $query = Menu::latest();
        if ($request->filled('category') && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $menus = $query->paginate(8)->withQueryString();
        $categories = Menu::distinct()->pluck('category');

        return view('admin.menu', compact(
            'menus',
            'categories',
            'totalMenus',
            'categoriesCount',
            'lowStockCount'
        ));
    }

    public function storeMenu(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'category' => 'required|string|max:60',
            'badge' => 'nullable|string|max:40',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
'image' => 'nullable|image|max:10240', 
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $data['is_available'] = $request->boolean('is_available', true) && $data['stock'] > 0;
        Menu::create($data);

        return redirect()->route('admin.menu')->with('success', 'Menu baru berhasil ditambahkan!');
    }

    public function editMenu(Menu $menu)
    {
        return view('admin.edit-menu', compact('menu'));
    }

    public function updateMenu(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'category' => 'required|string|max:60',
            'badge' => 'nullable|string|max:40',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $data['is_available'] = $request->has('is_available') && $data['stock'] > 0;
        $menu->update($data);

        return redirect()->route('admin.menu')->with('success', "Menu {$menu->name} berhasil diperbarui!");
    }

    public function destroyMenu(Menu $menu)
    {
        $menu->delete();
        return back()->with('success', 'Menu berhasil dihapus.');
    }

    // 4. MANAJEMEN KURSI & MEJA
    public function kursi()
    {
        $tables = Table::orderBy('id')->get();
        $totalTables = $tables->count();
        $occupiedCount = $tables->where('status', 'occupied')->count();
        $availableCount = $tables->where('status', 'available')->count();
        $reservedCount = $tables->where('status', 'reserved')->count();

        return view('admin.kursi', compact(
            'tables',
            'totalTables',
            'occupiedCount',
            'availableCount',
            'reservedCount'
        ));
    }

    public function storeTable(Request $request)
    {
        $data = $request->validate([
            'table_number' => 'required|string|max:30|unique:tables,table_number',
            'capacity' => 'required|integer|min:1|max:20',
        ]);

        $data['status'] = 'available';
        Table::create($data);

        return back()->with('success', 'Meja baru berhasil ditambahkan!');
    }

    public function updateTable(Request $request, Table $table)
    {
        $data = $request->validate([
            'table_number' => 'nullable|string|max:30|unique:tables,table_number,' . $table->id,
            'capacity' => 'nullable|integer|min:1|max:20',
            'status' => 'required|in:available,occupied,reserved,maintenance',
        ]);

        $table->update(array_filter($data, fn ($v) => !is_null($v)));

        return back()->with('success', "Status {$table->table_number} diperbarui.");
    }

    public function destroyTable(Table $table)
    {
        $table->delete();
        return back()->with('success', 'Meja berhasil dihapus.');
    }

    // 5. LAPORAN RESTORAN & EXPORT EXCEL
    public function laporan()
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_price');
        $totalOrders = Order::where('payment_status', 'paid')->count();
        $avgOrder = $totalOrders > 0 ? round($totalRevenue / $totalOrders) : 0;

        // Grafik Pendapatan 6 Bulan Terakhir
        $monthlyChart = collect();
        $monthsIndo = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $rev = Order::where('payment_status', 'paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_price');
            $monthlyChart->push([
                'month' => $monthsIndo[$date->month],
                'total' => $rev,
                'is_current' => $i === 0,
            ]);
        }
        $maxMonthly = max($monthlyChart->max('total'), 1);

        // Tabel Penjualan Menu Terlaris
        $menuSales = OrderItem::select('menu_id', DB::raw('SUM(quantity) as sold'), DB::raw('SUM(subtotal) as revenue'))
            ->with('menu')
            ->groupBy('menu_id')
            ->orderByDesc('sold')
            ->get();

        // Laporan Harian Minggu Ini
        $dailySales = collect();
        $daysFull = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayOrders = Order::where('payment_status', 'paid')->whereDate('created_at', $date->toDateString());
            $dailySales->push([
                'day' => $daysFull[$date->dayOfWeek],
                'date' => $date->format('d M'),
                'count' => (clone $dayOrders)->count(),
                'revenue' => (clone $dayOrders)->sum('total_price'),
            ]);
        }

        return view('admin.laporan', compact(
            'totalRevenue',
            'totalOrders',
            'avgOrder',
            'monthlyChart',
            'maxMonthly',
            'menuSales',
            'dailySales'
        ));
    }

    public function exportExcel()
    {
        $orders = Order::with(['user', 'table', 'items.menu'])
            ->where('payment_status', 'paid')
            ->latest()
            ->get();

        $filename = 'Laporan_Keuangan_Sate_Thaichan_Oks_' . date('Y-m-d') . '.xls';

        return response()
            ->view('admin.export_excel', compact('orders'))
            ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    // 6. KELOLA AKUN KARYAWAN / KASIR
    public function kasir()
    {
        $kasirs = User::where('role', 'kasir')->latest()->get();
        return view('admin.kasir', compact('kasirs'));
    }

    public function storeKasir(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'kasir',
            'email_verified_at' => now(),
        ]);

        return back()->with('success', "Akun Kasir untuk {$request->name} berhasil dibuat!");
    }

    public function destroyKasir(User $user)
    {
        if ($user->role === 'kasir') {
            $user->delete();
        }
        return back()->with('success', 'Akun kasir berhasil dihapus.');
    }
}