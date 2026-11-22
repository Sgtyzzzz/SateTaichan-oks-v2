@extends('layouts.admin')
@section('title', 'Laporan Restoran')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="font-serif-display text-3xl font-extrabold">Laporan Restoran</h1>
            <p class="text-xs text-stone-500 mt-1">Analisis performa finansial dan penjualan sate terbaru</p>
        </div>

        <a href="{{ route('admin.export') }}"
           class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm">
            📥 Export Laporan Excel (.XLS)
        </a>
    </div>

    <!-- 4 KARTU RINGKASAN LAPORAN -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">TOTAL PENDAPATAN</p>
            <div class="flex items-end justify-between mt-2">
                <span class="font-serif-display text-2xl font-extrabold">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold">Lunas</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">TOTAL PESANAN</p>
            <div class="flex items-end justify-between mt-2">
                <span class="font-serif-display text-3xl font-extrabold">{{ number_format($totalOrders) }}</span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold">Transaksi</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">RATA-RATA PESANAN</p>
            <div class="flex items-end justify-between mt-2">
                <span class="font-serif-display text-2xl font-extrabold">Rp{{ number_format($avgOrder, 0, ',', '.') }}</span>
                <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold">Per Nota</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">TINGKAT KEPUASAN</p>
            <div class="flex items-end justify-between mt-2">
                <span class="font-serif-display text-3xl font-extrabold">★ 4.8/5</span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold">+0.3</span>
            </div>
        </div>
    </div>

    <!-- TENGAH: PENDAPATAN BULANAN + DISTRIBUSI KATEGORI -->
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-stone-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="font-bold text-sm">Pendapatan Bulanan</h2>
                    <p class="text-[11px] text-stone-400">Perbandingan tren omzet panggangan (6 Bulan Terakhir)</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold">● Target Terpenuhi</span>
            </div>

            <div class="grid grid-cols-6 gap-4 items-end h-44 pt-8 px-4">
                @foreach($monthlyChart as $m)
                    @php $h = max(round(($m['total'] / $maxMonthly) * 100), 12); @endphp
                    <div class="flex flex-col items-center gap-2 h-full justify-end">
                        @if($m['is_current'])
                            <span class="px-2 py-0.5 rounded bg-[#181614] text-white text-[9px] font-bold">
                                Rp{{ number_format($m['total'] / 1000, 0) }}k
                            </span>
                        @endif
                        <div class="w-8 rounded-t-lg {{ $m['is_current'] ? 'bg-[#E0432D]' : 'bg-[#DED6CC]' }}"
                             style="height: {{ $h }}%"></div>
                        <span class="text-[11px] font-semibold {{ $m['is_current'] ? 'text-[#E0432D]' : 'text-stone-400' }}">
                            {{ $m['month'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="font-bold text-sm">Distribusi Kategori</h2>
                <p class="text-[11px] text-stone-400">Porsi penjualan berdasarkan kategori hidangan</p>
            </div>

            <div class="flex items-center justify-around my-4">
                <div class="w-28 h-28 rounded-full border-[12px] border-[#E0432D] flex items-center justify-center font-extrabold text-sm">
                    100%
                </div>
                <div class="space-y-2.5 text-xs">
                    <div>
                        <div class="font-bold flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-[#E0432D]"></span> Sate & Paket</div>
                        <div class="text-[10px] text-stone-400 pl-3.5">Menu Utama</div>
                    </div>
                    <div>
                        <div class="font-bold flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Sampingan</div>
                        <div class="text-[10px] text-stone-400 pl-3.5">Lontong & Kulit</div>
                    </div>
                    <div>
                        <div class="font-bold flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-sky-500"></span> Minuman</div>
                        <div class="text-[10px] text-stone-400 pl-3.5">Pendamping Segar</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BAWAH: LAPORAN PENJUALAN MENU + LAPORAN HARIAN -->
    <div class="grid lg:grid-cols-3 gap-6 items-start">
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4">
            <div>
                <h2 class="font-bold text-sm">Laporan Penjualan Menu</h2>
                <p class="text-[11px] text-stone-400">Detail statistik menu terlaris</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-stone-100 text-[10px] uppercase text-stone-400">
                            <th class="py-3">NO</th>
                            <th class="py-3">NAMA MENU</th>
                            <th class="py-3">KATEGORI</th>
                            <th class="py-3">TERJUAL</th>
                            <th class="py-3">PENDAPATAN</th>
                            <th class="py-3 text-right">KONTRIBUSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($menuSales as $idx => $ms)
                            @php $pct = $totalRevenue > 0 ? round(($ms->revenue / $totalRevenue) * 100) : 0; @endphp
                            <tr>
                                <td class="py-3 font-bold text-[#E0432D]">{{ $idx + 1 }}</td>
                                <td class="py-3 font-bold">{{ $ms->menu->name ?? 'Menu' }}</td>
                                <td class="py-3 text-stone-500">{{ $ms->menu->category ?? '-' }}</td>
                                <td class="py-3 font-semibold">{{ $ms->sold }}</td>
                                <td class="py-3 font-bold text-[#E0432D]">Rp{{ number_format($ms->revenue, 0, ',', '.') }}</td>
                                <td class="py-3 text-right text-stone-500">{{ $pct }}%</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-6 text-center text-stone-400">Belum ada data penjualan menu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4">
            <div>
                <h2 class="font-bold text-sm">Laporan Harian</h2>
                <p class="text-[11px] text-stone-400">Catatan aktivitas penjualan minggu ini</p>
            </div>

            <div class="divide-y divide-stone-100 text-xs">
                @foreach($dailySales as $ds)
                <div class="py-3 flex items-center justify-between">
                    <div>
                        <div class="font-bold">{{ $ds['day'] }}</div>
                        <div class="text-[10px] text-stone-400">{{ $ds['date'] }}</div>
                    </div>
                    <div class="text-center">
                        <div class="font-bold">{{ $ds['count'] }}</div>
                        <div class="text-[9px] text-stone-400">pesanan</div>
                    </div>
                    <div class="font-bold text-[#E0432D]">
                        Rp{{ number_format($ds['revenue'], 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection