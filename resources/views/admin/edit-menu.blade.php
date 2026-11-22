@extends('layouts.admin')
@section('title', 'Edit Menu Makanan')

@section('content')
    <div>
        <div class="text-xs text-stone-400 mb-1">
            <a href="{{ route('admin.menu') }}" class="hover:text-[#1A1816]">Menu</a>
            <span class="mx-1.5">›</span>
            <span class="text-[#E0432D] font-semibold">Edit Menu</span>
        </div>
        <h1 class="font-serif-display text-3xl font-extrabold">Edit Menu Makanan</h1>
        <p class="text-xs text-stone-500 mt-1">Edit detail menu yang sudah ada</p>
    </div>

    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data"
          class="bg-white p-8 rounded-2xl border border-stone-200 shadow-sm max-w-4xl">
        @csrf
        @method('PUT')

        <div class="grid md:grid-cols-3 gap-8">
            <!-- KOLOM KIRI: FOTO & TOGGLE STATUS -->
            <div class="space-y-4">
                <label class="block text-xs font-bold">Foto Menu</label>
                <img id="previewImg"
                     src="{{ $menu->image ? asset('storage/' . $menu->image) : 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=600&q=80' }}"
                     alt="{{ $menu->name }}"
                     class="w-full h-52 object-cover rounded-2xl border border-stone-200">

                <label class="w-full py-2.5 px-4 rounded-xl border border-stone-200 hover:bg-stone-50 flex items-center justify-center gap-2 text-xs font-semibold cursor-pointer">
                    <span>🖼️ Ganti Foto</span>
                    <input type="file" name="image" accept="image/*" class="hidden"
                           onchange="document.getElementById('previewImg').src = window.URL.createObjectURL(this.files[0])">
                </label>

                <div class="p-3.5 rounded-xl border border-stone-200 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold">Status Menu</div>
                        <div class="text-[10px] text-stone-400">Tersedia untuk dipesan</div>
                    </div>
                    <input type="checkbox" name="is_available" value="1" {{ $menu->is_available ? 'checked' : '' }}
                           class="w-4 h-4 accent-emerald-600">
                </div>
            </div>

            <!-- KOLOM KANAN: INPUT DETAIL -->
            <div class="md:col-span-2 space-y-4 text-xs">
                <div>
                    <label class="block font-bold mb-1.5">Nama Menu <span class="text-[#E0432D]">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $menu->name) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-stone-200 outline-none focus:border-[#E0432D]">
                </div>

                <div>
                    <label class="block font-bold mb-1.5">Kategori <span class="text-[#E0432D]">*</span></label>
                    <select name="category" required class="w-full px-4 py-2.5 rounded-xl border border-stone-200 outline-none focus:border-[#E0432D] bg-white">
                        @foreach(['Sate', 'Sampingan', 'Minuman', 'Paket'] as $cat)
                            <option value="{{ $cat }}" {{ $menu->category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold mb-1.5">Harga (Rp) <span class="text-[#E0432D]">*</span></label>
                        <input type="number" name="price" value="{{ old('price', $menu->price) }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-200 outline-none focus:border-[#E0432D]">
                    </div>
                    <div>
                        <label class="block font-bold mb-1.5">Stok Harian <span class="text-[#E0432D]">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', $menu->stock) }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-stone-200 outline-none focus:border-[#E0432D]">
                    </div>
                </div>

                <div>
                    <label class="block font-bold mb-1.5">Deskripsi Menu</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2.5 rounded-xl border border-stone-200 outline-none focus:border-[#E0432D]">{{ old('description', $menu->description) }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <a href="{{ route('admin.menu') }}" class="px-5 py-2.5 rounded-xl border border-stone-200 hover:bg-stone-50 font-semibold">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#E0432D] hover:bg-[#c83723] text-white font-semibold cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection