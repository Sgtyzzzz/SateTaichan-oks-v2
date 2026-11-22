<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun Baru — Sate Thaichan Oks</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FDF8F2] text-[#1A1816] min-h-screen grid grid-cols-1 lg:grid-cols-2">

    <!-- PANEL KIRI: FOTO SATE THAICHAN -->
    <div class="hidden lg:block relative bg-[#181614] overflow-hidden">
        <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=1200&q=80"
             alt="Sate Thaichan Oks"
             class="w-full h-full object-cover opacity-80">
        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent flex flex-col justify-end p-12 text-white">
            <span class="inline-block w-fit px-3 py-1 rounded-full bg-[#E0432D] text-[10px] font-bold uppercase tracking-widest mb-3">
                100% Daging Ayam Pilihan
            </span>
            <h2 class="font-serif-display text-4xl font-extrabold leading-tight">
                Pedasnya Nampol,<br>Gurihnya Bikin Nagih.
            </h2>
            <p class="text-xs text-stone-300 mt-3 max-w-md leading-relaxed">
                Pesan lebih cepat, pilih meja favoritmu sebelum datang, dan nikmati sate taichan yang dibakar segar saat dipesan.
            </p>
        </div>
    </div>

    <!-- PANEL KANAN: FORM REGISTER -->
    <div class="flex flex-col justify-between p-8 sm:p-12">
        <div class="flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-bold text-sm">
                <span class="w-8 h-8 rounded-lg bg-[#E0432D] text-white flex items-center justify-center">🔥</span>
                <span>Sate Thaichan Oks</span>
            </a>
            <a href="{{ route('home') }}" class="text-xs text-stone-500 hover:text-[#1A1816] font-medium">
                ← Kembali ke beranda
            </a>
        </div>

        <div class="w-full max-w-md mx-auto my-8 space-y-5">
            <div>
                <h1 class="font-serif-display text-3xl font-extrabold">Buat akun baru</h1>
                <p class="text-xs text-stone-500 mt-1">Daftar sekarang dengan email aktif untuk memesan sate taichan.</p>
            </div>

            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs space-y-1">
                    @foreach($errors->all() as $err)
                        <div>• {{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <!-- Daftar Cepat Google -->
            <a href="{{ route('google.login') }}"
               class="w-full py-2.5 px-4 rounded-xl bg-white border border-stone-200 hover:bg-stone-50 flex items-center justify-center gap-2.5 text-xs font-semibold shadow-sm transition">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-4 h-4">
                <span>Daftar dengan Google</span>
            </a>

            <div class="relative flex items-center justify-center">
                <div class="border-t border-stone-200 w-full"></div>
                <span class="bg-[#FDF8F2] px-3 text-[10px] font-bold text-stone-400 uppercase">ATAU</span>
                <div class="border-t border-stone-200 w-full"></div>
            </div>

            <!-- Form Pendaftaran Tunggal -->
            <form action="{{ route('register.pelanggan') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold mb-1">Nama lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama kamu"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-stone-200 outline-none focus:border-[#E0432D]">
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Nomor WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-stone-200 outline-none focus:border-[#E0432D]">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Email (Real & Aktif)</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@gmail.com"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-stone-200 outline-none focus:border-[#E0432D]">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold mb-1">Kata sandi</label>
                        <input type="password" name="password" required placeholder="Min. 8 karakter"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-stone-200 outline-none focus:border-[#E0432D]">
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Konfirmasi kata sandi</label>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-stone-200 outline-none focus:border-[#E0432D]">
                    </div>
                </div>

                <label class="flex items-center gap-2 text-stone-600 cursor-pointer">
                    <input type="checkbox" required class="accent-[#E0432D] rounded">
                    <span>Saya menyetujui <strong class="text-[#E0432D]">Syarat & Ketentuan</strong> dan <strong class="text-[#E0432D]">Kebijakan Privasi</strong>.</span>
                </label>

                <button type="submit" class="w-full py-3 rounded-xl bg-[#E0432D] hover:bg-[#c83723] text-white font-semibold shadow-sm transition cursor-pointer">
                    Daftar sekarang
                </button>
            </form>

            <p class="text-center text-xs text-stone-500">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-[#E0432D] font-bold hover:underline">Masuk di sini</a>
            </p>
        </div>

        <p class="text-center text-[11px] text-stone-400">
            © {{ date('Y') }} Sate Thaichan Oks • Dibuat hangat dari panggangan.
        </p>
    </div>

</body>
</html>