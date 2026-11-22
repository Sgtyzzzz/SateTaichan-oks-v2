@extends('layouts.admin')
@section('title', 'Pengelolaan Menu Makanan')

@section('content')
    <div>
        <h1 class="font-serif-display text-3xl font-extrabold">Pengelolaan Menu Makanan</h1>
        <p class="text-xs text-stone-500 mt-1">Kelola menu restaurant</p>
    </div>

    <!-- 3 KARTU STATISTIK MENU -->
    <div class="grid md:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">TOTAL MENU</p>
            <p class="font-serif-display text-2xl font-extrabold mt-1">{{ $totalMenus }} Menu Aktif</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">KATEGORI MAKANAN DAN MINUMAN</p>
            <p class="font-serif-display text-2xl font-extrabold text-[#E0432D] mt-1">{{ $categoriesCount }} Macam</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">MENU MENIPIS / HABIS</p>
            <p class="font-serif-display text-2xl font-extrabold text-amber-600 mt-1">{{ $lowStockCount }} Perlu Restock</p>
        </div>
    </div>

    <!-- SEARCH BAR -->
    <form method="GET" action="{{ route('admin.menu') }}" class="max-w-xs">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Cari sate atau menu..."
               class="w-full px-4 py-2.5 rounded-xl bg-white border border-stone-200 text-xs outline-none focus:border-[#E0432D]">
    </form>

    <!-- KONTEN UTAMA: TABEL KIRI + FORM INPUT KANAN -->
    <div class="grid lg:grid-cols-3 gap-6 items-start">
        <!-- TABEL MENU (2 KOLOM) -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-5">
            <!-- Filter Pill Kategori -->
            <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                <a href="{{ route('admin.menu') }}"
                   class="px-3.5 py-1.5 rounded-lg {{ !request('category') || request('category') === 'Semua' ? 'bg-red-50 text-[#E0432D]' : 'bg-[#FDF8F2] text-stone-600 hover:bg-stone-100' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('admin.menu', ['category' => $cat]) }}"
                       class="px-3.5 py-1.5 rounded-lg {{ request('category') === $cat ? 'bg-red-50 text-[#E0432D]' : 'bg-[#FDF8F2] text-stone-600 hover:bg-stone-100' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-stone-100 text-[10px] uppercase text-stone-400">
                            <th class="py-3">NAMA & DETAIL MENU</th>
                            <th class="py-3">KATEGORI</th>
                            <th class="py-3">HARGA</th>
                            <th class="py-3">STOK</th>
                            <th class="py-3">STATUS</th>
                            <th class="py-3 text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($menus as $menu)
                        <tr>
                            <td class="py-3.5 flex items-center gap-3">
                                <img src="{{ $menu->image ? asset('storage/' . $menu->image) : 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=200&q=80' }}"
                                     alt="{{ $menu->name }}"
                                     class="w-11 h-11 rounded-xl object-cover border border-stone-200 shrink-0">
                                <div>
                                    <div class="font-bold">{{ $menu->name }}</div>
                                    <div class="text-[10px] text-stone-400">ID: OKS-M0{{ $menu->id }}</div>
                                </div>
                            </td>
                            <td class="py-3.5 text-stone-600">{{ $menu->category }}</td>
                            <td class="py-3.5 font-bold text-[#E0432D]">Rp{{ number_format($menu->price, 0, ',', '.') }}</td>
                            <td class="py-3.5 font-semibold">{{ $menu->stock }} Porsi</td>
                            <td class="py-3.5">
                                @if($menu->stock > 0 && $menu->is_available)
                                    <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-600 text-[10px] font-bold">Tersedia</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-md bg-red-50 text-red-600 text-[10px] font-bold">Habis</span>
                                @endif
                            </td>
                            <td class="py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.menus.edit', $menu->id) }}"
                                       class="p-2 rounded-lg border border-stone-200 hover:bg-stone-100 text-stone-700" title="Edit Menu">
                                        ✏️
                                    </a>
                                    <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Hapus menu {{ $menu->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 cursor-pointer" title="Hapus">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="py-8 text-center text-stone-400">Belum ada data menu.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-2">
                {{ $menus->links() }}
            </div>
        </div>

        <!-- FORM INPUT MENU BARU (1 KOLOM KANAN) -->
        <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4">
            <div>
                <h2 class="font-bold text-sm">Form Input Menu</h2>
                <p class="text-[11px] text-stone-400">Tambahkan menu baru ke katalog restoran.</p>
            </div>

            <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold mb-1">Nama Menu <span class="text-[#E0432D]">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Sate Mozzarella"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 outline-none focus:border-[#E0432D]">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Kategori <span class="text-[#E0432D]">*</span></label>
                    <select name="category" required class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 outline-none focus:border-[#E0432D] bg-white">
                        <option value="Sate">Sate</option>
                        <option value="Sampingan">Sampingan</option>
                        <option value="Minuman">Minuman</option>
                        <option value="Paket">Paket</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold mb-1">Harga (Rp) <span class="text-[#E0432D]">*</span></label>
                        <input type="number" name="price" required placeholder="35000"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 outline-none focus:border-[#E0432D]">
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Stok Harian <span class="text-[#E0432D]">*</span></label>
                        <input type="number" name="stock" required placeholder="45"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 outline-none focus:border-[#E0432D]">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" placeholder="Sate taichan lumer keju premium..."
                              class="w-full px-3.5 py-2 rounded-xl border border-stone-200 outline-none focus:border-[#E0432D]"></textarea>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <div>
                        <div class="font-semibold">Status Menu</div>
                        <div class="text-[10px] text-stone-400">Tersedia untuk dipesan</div>
                    </div>
                    <input type="checkbox" name="is_available" value="1" checked class="w-4 h-4 accent-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Foto Menu</label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full text-[11px] file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-[#FDF8F2] file:text-[#1A1816] file:font-semibold border border-dashed border-stone-300 rounded-xl p-2">
                </div>

                <div class="grid grid-cols-2 gap-2.5 pt-2">
                    <button type="reset" class="py-2.5 rounded-xl border border-stone-200 hover:bg-stone-50 font-semibold cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="py-2.5 rounded-xl bg-[#E0432D] hover:bg-[#c83723] text-white font-semibold cursor-pointer">
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection