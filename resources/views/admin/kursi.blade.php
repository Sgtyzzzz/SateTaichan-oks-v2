@extends('layouts.admin')
@section('title', 'Manajemen Kursi & Meja')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="font-serif-display text-3xl font-extrabold">Manajemen Kursi & Meja</h1>
            <p class="text-xs text-stone-500 mt-1">Kelola status meja dan kursi restoran secara real-time</p>
        </div>

        <!-- Form Tambah Meja Baru -->
        <form action="{{ route('admin.tables.store') }}" method="POST" class="flex items-center gap-2 text-xs">
            @csrf
            <input type="text" name="table_number" required placeholder="Nama Meja (mis: Meja 9)"
                   class="px-3.5 py-2.5 rounded-xl bg-white border border-stone-200 outline-none focus:border-[#E0432D] w-44">
            <input type="number" name="capacity" required value="4" min="1" max="20" placeholder="Kursi"
                   class="px-3 py-2.5 rounded-xl bg-white border border-stone-200 outline-none focus:border-[#E0432D] w-20">
            <button type="submit" class="px-4 py-2.5 rounded-xl bg-[#E0432D] hover:bg-[#c83723] text-white font-semibold cursor-pointer">
                + Tambah Meja
            </button>
        </form>
    </div>

    <!-- 4 KARTU STATISTIK MEJA -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">TOTAL MEJA</p>
            <div class="mt-2">
                <span class="font-serif-display text-3xl font-extrabold">{{ $totalTables }}</span>
                <p class="text-[10px] text-stone-400 mt-1">Semua Meja Terdaftar</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">MEJA TERISI</p>
            <div class="flex items-center justify-between mt-2">
                <span class="font-serif-display text-3xl font-extrabold">{{ $occupiedCount }}</span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold">● Terisi</span>
            </div>
            <p class="text-[10px] text-stone-400 mt-1">Pelanggan Aktif Makan</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">MEJA TERSEDIA</p>
            <div class="flex items-center justify-between mt-2">
                <span class="font-serif-display text-3xl font-extrabold">{{ $availableCount }}</span>
                <span class="px-2.5 py-0.5 rounded-full bg-sky-50 text-sky-600 text-[10px] font-bold">✓ Siap</span>
            </div>
            <p class="text-[10px] text-stone-400 mt-1">Dapat Langsung Digunakan</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">MEJA DIPESAN</p>
            <div class="flex items-center justify-between mt-2">
                <span class="font-serif-display text-3xl font-extrabold">{{ $reservedCount }}</span>
                <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold">★ Reserved</span>
            </div>
            <p class="text-[10px] text-stone-400 mt-1">Booking via Aplikasi</p>
        </div>
    </div>

    <!-- DENAH MEJA RESTORAN -->
    <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-5">
        <div>
            <h2 class="font-bold text-sm">Denah Meja Restoran</h2>
            <p class="text-[11px] text-stone-400">Tata letak dan status penggunaan meja makan saat ini</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($tables as $table)
                @php
                    $cardStyle = match($table->status) {
                        'available' => 'border-emerald-300 bg-emerald-50/20',
                        'occupied' => 'border-red-300 bg-red-50/20',
                        'reserved' => 'border-amber-300 bg-amber-50/20',
                        default => 'border-stone-300 bg-stone-50/50',
                    };
                    $badgeStyle = match($table->status) {
                        'available' => 'bg-emerald-50 text-emerald-600',
                        'occupied' => 'bg-red-50 text-red-600',
                        'reserved' => 'bg-amber-50 text-amber-600',
                        default => 'bg-stone-200 text-stone-600',
                    };
                    $statusLabel = match($table->status) {
                        'available' => 'Tersedia',
                        'occupied' => 'Terisi',
                        'reserved' => 'Dipesan',
                        default => 'Maintenance',
                    };
                @endphp

                <div class="p-4 rounded-2xl border-2 {{ $cardStyle }} flex flex-col justify-between space-y-4">
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-sm">{{ $table->table_number }}</span>
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold {{ $badgeStyle }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                        <div class="text-xs text-stone-500">👥 Kapasitas: {{ $table->capacity }} Kursi</div>
                    </div>

                    <div class="space-y-2 pt-2">
                        <form action="{{ route('admin.tables.update', $table->id) }}" method="POST" class="flex items-center gap-1.5 text-[11px]">
                            @csrf
                            @method('PUT')
                            <select name="status" class="w-full px-2.5 py-1.5 rounded-lg bg-white border border-stone-200 font-semibold outline-none">
                                <option value="available" {{ $table->status === 'available' ? 'selected' : '' }}>Tersedia</option>
                                <option value="occupied" {{ $table->status === 'occupied' ? 'selected' : '' }}>Terisi</option>
                                <option value="reserved" {{ $table->status === 'reserved' ? 'selected' : '' }}>Dipesan</option>
                                <option value="maintenance" {{ $table->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-[#E0432D] hover:bg-[#c83723] text-white font-semibold shrink-0 cursor-pointer">
                                Ubah
                            </button>
                        </form>

                        <form action="{{ route('admin.tables.destroy', $table->id) }}" method="POST" onsubmit="return confirm('Hapus {{ $table->table_number }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full py-1 text-[10px] text-stone-400 hover:text-red-600 font-semibold cursor-pointer">
                                Hapus Meja
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="col-span-4 text-center py-8 text-xs text-stone-400">Belum ada meja terdaftar.</p>
            @endforelse
        </div>
    </div>
@endsection