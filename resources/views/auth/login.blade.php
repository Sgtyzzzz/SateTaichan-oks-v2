<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Sate Thaichan Oks</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FDF8F2] text-[#1A1816] min-h-screen flex">

    <!-- PANEL KIRI (FOTO & QUOTE) -->
    <div class="hidden lg:flex lg:w-[45%] relative bg-[#141210] text-white flex-col justify-between p-10 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=1200&q=80"
             alt="Sate Thaichan Oks"
             class="absolute inset-0 w-full h-full object-cover opacity-50">
        <div class="absolute inset-0 bg-gradient-to-t from-[#141210] via-[#141210]/40 to-[#141210]/70"></div>

        <!-- Logo Top Left -->
        <a href="{{ route('home') }}" class="relative z-10 flex items-center gap-2.5 font-bold text-sm">
            <span class="w-8 h-8 rounded-lg bg-[#E0432D] flex items-center justify-center text-white shadow">🔥</span>
            <span>Sate Thaichan Oks</span>
        </a>

        <!-- Headline Bottom Left -->
        <div class="relative z-10 max-w-md">
            <span class="inline-flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-stone-300 mb-3">
                <span class="w-2 h-2 rounded-full bg-[#8CC63F]"></span>
                Rasa Yang Bikin Pulang
            </span>
            <h1 class="font-serif-display text-4xl font-extrabold leading-tight">
                Satu tusuk, satu cerita. Masuk untuk pesan favoritmu.
            </h1>
            <div class="mt-5 flex items-center gap-3 text-xs text-stone-300">
                <span class="w-8 h-0.5 bg-[#E0432D]"></span>
                <span>Dibakar saat dipesan • Pedasnya bisa dipilih</span>
            </div>
        </div>
    </div>

    <!-- PANEL KANAN (FORM LOGIN) -->
    <div class="w-full lg:w-[55%] flex flex-col justify-between px-6 sm:px-14 py-8">
        <!-- Header Top -->
        <div class="flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-bold text-sm">
                <span class="w-8 h-8 rounded-lg bg-[#E0432D] text-white flex items-center justify-center text-xs shadow-sm">🔥</span>
                <span>Sate Thaichan Oks</span>
            </a>
            <a href="{{ route('home') }}" class="text-xs font-medium text-stone-600 hover:text-[#1A1816] transition">
                ← Kembali ke beranda
            </a>
        </div>

        <!-- Form Center -->
        <div class="w-full max-w-md mx-auto my-auto py-8">
            <h2 class="font-serif-display text-3xl font-extrabold text-[#1A1816]">Selamat datang kembali</h2>
            <p class="text-xs text-stone-500 mt-1.5">Masuk untuk pesan lebih cepat, booking meja, dan kelola pesanan.</p>

            @if ($errors->any())
                <div class="mt-4 p-3 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Tombol Google -->
            <a href="{{ route('google.login') }}"
               class="mt-6 w-full py-2.5 px-4 rounded-xl bg-white border border-stone-200 hover:bg-stone-50 transition flex items-center justify-center gap-2.5 text-xs font-semibold text-stone-700 shadow-sm">
                <svg class="w-4 h-4" viewBox="0 0 24 24">
                    <path fill="#EA4335" d="M12 5c1.6 0 3 .6 4.1 1.6l3.1-3.1C17.3 1.7 14.8 1 12 1 7.4 1 3.5 3.6 1.6 7.4l3.6 2.8C6.1 7.2 8.8 5 12 5z"/>
                    <path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.6-.2-2.3H12v4.5h6.5c-.3 1.5-1.1 2.8-2.4 3.6l3.7 2.9c2.2-2 3.7-5 3.7-8.7z"/>
                    <path fill="#FBBC05" d="M5.2 14.8c-.2-.7-.4-1.5-.4-2.3s.2-1.6.4-2.3L1.6 7.4C.6 9.4 0 11.6 0 14s.6 4.6 1.6 6.6l3.6-2.8z"/>
                    <path fill="#34A853" d="M12 24c3.2 0 6-1.1 8-3l-3.7-2.9c-1.1.7-2.5 1.2-4.3 1.2-3.2 0-5.9-2.2-6.8-5.1l-3.6 2.8C3.5 21.4 7.4 24 12 24z"/>
                </svg>
                Masuk dengan Google
            </a>

            <div class="relative my-6 text-center">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-stone-200"></div></div>
                <span class="relative bg-[#FDF8F2] px-3 text-[10px] font-semibold uppercase tracking-widest text-stone-400">Atau</span>
            </div>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                           class="w-full px-4 py-2.5 rounded-xl bg-white border border-stone-200 focus:border-[#E0432D] focus:ring-2 focus:ring-[#E0432D]/15 outline-none text-xs transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1.5">Kata sandi</label>
                    <div class="relative">
                        <input type="password" id="loginPassword" name="password" required placeholder="Masukkan kata sandi"
                               class="w-full px-4 py-2.5 rounded-xl bg-white border border-stone-200 focus:border-[#E0432D] focus:ring-2 focus:ring-[#E0432D]/15 outline-none text-xs transition">
                        <button type="button" onclick="togglePass('loginPassword')" class="absolute right-3.5 top-2.5 text-stone-400 hover:text-stone-600 text-xs">👁️</button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="inline-flex items-center gap-2 cursor-pointer text-stone-600">
                        <input type="checkbox" name="remember" class="rounded accent-[#E0432D]">
                        <span>Ingat saya</span>
                    </label>
                    <a href="#" class="text-[#E0432D] font-semibold hover:underline">Lupa kata sandi?</a>
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-xl bg-[#E0432D] hover:bg-[#c83723] text-white font-semibold text-xs transition shadow-md cursor-pointer">
                    Masuk
                </button>
            </form>

            <p class="mt-6 text-center text-xs text-stone-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-[#E0432D] font-semibold hover:underline">Daftar sekarang</a>
            </p>
        </div>

        <!-- Footer -->
        <p class="text-center text-[11px] text-stone-400">
            © {{ date('Y') }} Sate Thaichan Oks • Dibuat hangat dari panggangan.
        </p>
    </div>

    <script>
        function togglePass(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>