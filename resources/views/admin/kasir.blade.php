@extends('layouts.admin')
@section('title', 'Kelola Akun Kasir')

@section('content')
    <div>
        <h1 class="font-serif-display text-3xl font-extrabold">Kelola Akun Karyawan / Kasir</h1>
        <p class="text-xs text-stone-500 mt-1">Buat dan kelola akun login untuk kasir yang bertugas di outlet</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 items-start">
        <!-- Form Tambah Akun Kasir -->
        <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4">
            <div>
                <h2 class="font-bold text-sm">Tambah Akun Kasir Baru</h2>
                <p class="text-[11px] text-stone-400">Akun langsung aktif dan siap login ke Kasir Console.</p>
            </div>

            <form action="{{ route('admin.kasir.store') }}" method="POST" class="space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold mb-1">Nama Lengkap Kasir <span class="text-[#E0432D]">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Dewi Lestari"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 outline-none focus:border-[#E0432D]">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Nomor WhatsApp <span class="text-[#E0432D]">*</span></label>
                    <input type="text" name="phone" required placeholder="0812xxxxxxxx"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 outline-none focus:border-[#E0432D]">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Email Login <span class="text-[#E0432D]">*</span></label>
                    <input type="email" name="email" required placeholder="kasir@satetaichan.com"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 outline-none focus:border-[#E0432D]">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Kata Sandi <span class="text-[#E0432D]">*</span></label>
                    <input type="password" name="password" required placeholder="Min. 8 karakter"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-stone-200 outline-none focus:border-[#E0432D]">
                </div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-[#E0432D] hover:bg-[#c83723] text-white font-semibold cursor-pointer">
                    + Buat Akun Kasir
                </button>
            </form>
        </div>

        <!-- Daftar Akun Kasir Aktif -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4">
            <div>
                <h2 class="font-bold text-sm">Daftar Kasir Terdaftar ({{ $kasirs->count() }})</h2>
                <p class="text-[11px] text-stone-400">Karyawan dengan akses ke Kasir Console</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-stone-100 text-[10px] uppercase text-stone-400">
                            <th class="py-3">NAMA KASIR</th>
                            <th class="py-3">EMAIL LOGIN</th>
                            <th class="py-3">WHATSAPP</th>
                            <th class="py-3">STATUS</th>
                            <th class="py-3 text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse($kasirs as $kasir)
                        <tr>
                            <td class="py-3.5 font-bold">{{ $kasir->name }}</td>
                            <td class="py-3.5 text-stone-600">{{ $kasir->email }}</td>
                            <td class="py-3.5 text-stone-500">{{ $kasir->phone ?? '-' }}</td>
                            <td class="py-3.5">
                                <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-600 text-[10px] font-bold">Aktif</span>
                            </td>
                            <td class="py-3.5 text-right">
                                <form action="{{ route('admin.kasir.destroy', $kasir->id) }}" method="POST" onsubmit="return confirm('Hapus akun kasir {{ $kasir->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 font-semibold cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-8 text-center text-stone-400">Belum ada akun kasir terdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection