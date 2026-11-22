@extends('layouts.admin')
@section('title', 'Riwayat Pesanan')

@section('content')
    <div>
        <h1 class="font-serif-display text-3xl font-extrabold">Riwayat Pesanan</h1>
        <p class="text-xs text-stone-500 mt-1">Kelola dan pantau semua pesanan restoran</p>
    </div>

    <!-- 3 KARTU RINGKASAN PESANAN -->
    <div class="grid md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">TOTAL PESANAN</p>
            <p class="font-serif-display text-3xl font-extrabold mt-1">{{ number_format($totalOrders) }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">PESANAN ONLINE</p>
            <p class="font-serif-display text-3xl font-extrabold mt-1">{{ number_format($onlineCount) }}</p>
            <p class="text-[10px] text-stone-400 mt-0.5">Pesanan via website</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">PESANAN OFFLINE</p>
            <p class="font-serif-display text-3xl font-extrabold mt-1">{{ number_format($offlineCount) }}</p>
            <p class="text-[10px] text-stone-400 mt-0.5">Pesanan di restoran</p>
        </div>
    </div>

    <!-- TAB SWITCHER ONLINE VS OFFLINE -->
    <div class="flex items-center gap-6 border-b border-stone-200 text-xs font-bold">
        <a href="{{ route('admin.pesanan', ['tab' => 'online']) }}"
           class="pb-3 border-b-2 transition {{ $tab === 'online' ? 'border-[#E0432D] text-[#E0432D]' : 'border-transparent text-stone-400 hover:text-stone-700' }}">
            Pesanan Online
        </a>
        <a href="{{ route('admin.pesanan', ['tab' => 'offline']) }}"
           class="pb-3 border-b-2 transition {{ $tab === 'offline' ? 'border-[#E0432D] text-[#E0432D]' : 'border-transparent text-stone-400 hover:text-stone-700' }}">
            Pesanan Offline
        </a>
    </div>

    <!-- FILTER & PENCARIAN -->
    <form method="GET" action="{{ route('admin.pesanan') }}" class="flex flex-wrap items-center justify-between gap-3 text-xs">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div class="flex flex-wrap items-center gap-2.5">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Cari nomor pesanan..."
                   class="px-4 py-2 rounded-xl bg-white border border-stone-200 outline-none focus:border-[#E0432D] w-60">

            <select name="status" class="px-3.5 py-2 rounded-xl bg-white border border-stone-200 outline-none">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="cooking" {{ request('status') === 'cooking' ? 'selected' : '' }}>Diproses</option>
                <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Siap Saji</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <button type="submit" class="px-4 py-2 rounded-xl bg-[#E0432D] hover:bg-[#c83723] text-white font-semibold cursor-pointer">
            Terapkan Filter
        </button>
    </form>

    <!-- TABEL DAFTAR PESANAN -->
    <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4">
        <div>
            <h2 class="font-bold text-sm">Daftar Pesanan {{ $tab === 'offline' ? 'Offline' : 'Online' }}</h2>
            <p class="text-[11px] text-stone-400">Pantau status pesanan dan pembayaran secara langsung</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-stone-100 text-[10px] uppercase text-stone-400">
                        <th class="py-3">NO. PESANAN</th>
                        <th class="py-3">PELANGGAN</th>
                        <th class="py-3">ITEM PESANAN</th>
                        <th class="py-3">TOTAL</th>
                        <th class="py-3">METODE BAYAR</th>
                        <th class="py-3">STATUS</th>
                        <th class="py-3 text-right">WAKTU</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($orders as $ord)
                    <tr class="{{ $ord->order_status === 'cancelled' ? 'bg-stone-50/70 text-stone-400 line-through' : '' }}">
                        <td class="py-3.5 font-bold text-[#E0432D]">#{{ $ord->order_code }}</td>
                        <td class="py-3.5 font-semibold">{{ $ord->customer_name ?? ($ord->user->name ?? 'Pelanggan') }}</td>
                        <td class="py-3.5 text-stone-500">
                            @foreach($ord->items as $it)
                                {{ $it->quantity }}x {{ $it->menu->name ?? 'Menu' }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td class="py-3.5 font-bold">Rp{{ number_format($ord->total_price, 0, ',', '.') }}</td>
                        <td class="py-3.5 uppercase">{{ $ord->payment_method }}</td>
                        <td class="py-3.5">
                            @if($ord->order_status === 'completed')
                                <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-600 text-[10px] font-bold">Selesai</span>
                            @elseif($ord->order_status === 'ready')
                                <span class="px-2.5 py-1 rounded-md bg-sky-50 text-sky-600 text-[10px] font-bold">Siap Saji</span>
                            @elseif($ord->order_status === 'cancelled')
                                <span class="px-2.5 py-1 rounded-md bg-red-50 text-red-600 text-[10px] font-bold">Dibatalkan</span>
                            @else
                                <span class="px-2.5 py-1 rounded-md bg-amber-50 text-amber-600 text-[10px] font-bold">Diproses</span>
                            @endif
                        </td>
                        <td class="py-3.5 text-right text-stone-400">{{ $ord->created_at->diffForHumans() }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="py-8 text-center text-stone-400">Tidak ada data pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-2">
            {{ $orders->links() }}
        </div>
    </div>
@endsection