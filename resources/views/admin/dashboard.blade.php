@extends('layouts.admin')
@section('title', 'Ringkasan Performa')

@section('content')
    <div>
        <h1 class="font-serif-display text-3xl font-extrabold">Ringkasan Performa</h1>
        <p class="text-xs text-stone-500 mt-1">Update panggangan hari ini • Live data</p>
    </div>

    <!-- 4 KARTU METRIK ATAS -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">TOTAL PESANAN HARI INI</p>
            <div class="flex items-end justify-between mt-2">
                <span class="font-serif-display text-3xl font-extrabold">{{ $todayOrdersCount }}</span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold">Live</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">PENDAPATAN HARI INI</p>
            <div class="flex items-end justify-between mt-2">
                <span class="font-serif-display text-2xl font-extrabold">Rp{{ number_format($todayRevenue, 0, ',', '.') }}</span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold">Lunas</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">PELANGGAN BARU</p>
            <div class="flex items-end justify-between mt-2">
                <span class="font-serif-display text-3xl font-extrabold">{{ $newCustomersCount }}</span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold">Hari ini</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">RATING RATA-RATA</p>
            <div class="flex items-end justify-between mt-2">
                <span class="font-serif-display text-3xl font-extrabold">★ 4.8</span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold">+0.2</span>
            </div>
        </div>
    </div>

    <!-- TENGAH: GRAFIK PENDAPATAN MINGGU INI + MENU TERLARIS -->
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-stone-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="font-bold text-sm">Pendapatan Minggu Ini</h2>
                    <p class="text-[11px] text-stone-400">Perbandingan performa harian sate terjual</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold">● Live Chart</span>
            </div>

            <div class="grid grid-cols-7 gap-3 items-end h-44 pt-8 px-2">
                @foreach($weeklyChart as $bar)
                    @php $height = max(round(($bar['total'] / $maxWeekly) * 100), 12); @endphp
                    <div class="flex flex-col items-center gap-2 h-full justify-end">
                        @if($bar['is_today'])
                            <span class="px-2 py-0.5 rounded bg-[#181614] text-white text-[9px] font-bold">
                                Rp{{ number_format($bar['total'] / 1000, 0) }}k
                            </span>
                        @endif
                        <div class="w-7 rounded-t-lg transition-all {{ $bar['is_today'] ? 'bg-[#E0432D]' : 'bg-[#DED6CC]' }}"
                             style="height: {{ $height }}%"></div>
                        <span class="text-[11px] font-semibold {{ $bar['is_today'] ? 'text-[#E0432D]' : 'text-stone-400' }}">
                            {{ $bar['day'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4">
            <div>
                <h2 class="font-bold text-sm">Menu Terlaris</h2>
                <p class="text-[11px] text-stone-400">Pilihan favorit pembeli</p>
            </div>

            <div class="space-y-3">
                @forelse($topMenus as $idx => $item)
                <div class="p-3 rounded-xl bg-[#FDF8F2] border border-stone-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-full {{ $idx === 0 ? 'bg-[#E0432D] text-white' : 'bg-stone-200 text-stone-700' }} text-[10px] font-bold flex items-center justify-center">
                            {{ $idx + 1 }}
                        </span>
                        <div>
                            <div class="text-xs font-bold">{{ $item->menu->name ?? 'Menu' }}</div>
                            <div class="text-[10px] text-stone-400">{{ $item->menu->category ?? 'Sate' }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-extrabold text-[#E0432D]">{{ $item->total_sold }}</div>
                        <div class="text-[9px] text-stone-400">porsi terjual</div>
                    </div>
                </div>
                @empty
                <p class="text-xs text-stone-400 py-6 text-center">Belum ada data penjualan menu.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- BAWAH: PESANAN TERBARU -->
    <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-sm">Pesanan Terbaru</h2>
                <p class="text-[11px] text-stone-400">Daftar antrean panggangan masuk real-time</p>
            </div>
            <a href="{{ route('admin.pesanan') }}" class="px-3.5 py-2 rounded-xl border border-stone-200 hover:bg-stone-50 text-xs font-semibold">
                Kelola Semua Pesanan →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-stone-100 text-[10px] uppercase text-stone-400">
                        <th class="py-3">NO. PESANAN</th>
                        <th class="py-3">PELANGGAN</th>
                        <th class="py-3">ITEM PESANAN</th>
                        <th class="py-3">TOTAL</th>
                        <th class="py-3">STATUS</th>
                        <th class="py-3 text-right">WAKTU</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($recentOrders as $ord)
                    <tr>
                        <td class="py-3.5 font-bold text-[#E0432D]">#{{ $ord->order_code }}</td>
                        <td class="py-3.5 font-semibold">{{ $ord->customer_name ?? ($ord->user->name ?? 'Pelanggan') }}</td>
                        <td class="py-3.5 text-stone-500">
                            @foreach($ord->items as $it)
                                {{ $it->quantity }}x {{ $it->menu->name ?? 'Menu' }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td class="py-3.5 font-bold">Rp{{ number_format($ord->total_price, 0, ',', '.') }}</td>
                        <td class="py-3.5">
                            @if($ord->order_status === 'completed')
                                <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-600 text-[10px] font-bold">Selesai</span>
                            @elseif($ord->order_status === 'cancelled')
                                <span class="px-2.5 py-1 rounded-md bg-red-50 text-red-600 text-[10px] font-bold">Dibatalkan</span>
                            @else
                                <span class="px-2.5 py-1 rounded-md bg-amber-50 text-amber-600 text-[10px] font-bold">Diproses</span>
                            @endif
                        </td>
                        <td class="py-3.5 text-right text-stone-400">{{ $ord->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-6 text-center text-stone-400">Belum ada pesanan masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection