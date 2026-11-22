<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sate Thaichan Oks — Satu tusuk, satu cerita rasa yang bikin pulang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FDF8F2] text-[#1A1816] antialiased">

    <!-- NAVBAR -->
    <header class="sticky top-0 z-50 bg-[#FDF8F2]/95 backdrop-blur border-b border-stone-200/70">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-bold text-base">
                <span class="w-8 h-8 rounded-lg bg-[#E0432D] text-white flex items-center justify-center text-sm shadow-sm">🔥</span>
                <span>Sate Thaichan Oks</span>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-stone-600">
                <a href="#beranda" class="text-[#1A1816] font-semibold">Beranda</a>
                <a href="#menu" class="hover:text-[#E0432D] transition">Menu</a>
                <a href="#tentang" class="hover:text-[#E0432D] transition">Tentang Kami</a>
                <a href="#lokasi" class="hover:text-[#E0432D] transition">Lokasi</a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    @php
                        $dashboardRoute = match(auth()->user()->role) {
                            'admin' => route('admin.dashboard'),
                            'kasir' => route('kasir.dashboard'),
                            default => route('pelanggan.menu'),
                        };
                    @endphp
                    <a href="{{ $dashboardRoute }}" class="px-4 py-2 text-xs font-semibold rounded-lg border border-stone-300 hover:bg-stone-100 transition">
                        Dashboard ({{ ucfirst(auth()->user()->role) }})
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-xs font-semibold rounded-lg border border-stone-300 hover:bg-stone-100 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-xs font-semibold rounded-lg bg-[#E0432D] text-white hover:bg-[#c83723] transition shadow-sm">
                        Pesan Sekarang
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section id="beranda" class="relative bg-[#141210] text-white overflow-hidden">
        <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=1600&q=80"
             alt="Sate Taichan Bakar"
             class="absolute inset-0 w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 bg-gradient-to-r from-[#141210] via-[#141210]/80 to-transparent"></div>

        <div class="relative max-w-6xl mx-auto px-6 py-24 md:py-32">
            <span class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-stone-300 mb-4">
                <span class="w-2 h-2 rounded-full bg-[#8CC63F]"></span>
                Dipanggang Hangat Setiap Hari
            </span>

            <h1 class="font-serif-display text-4xl md:text-6xl font-extrabold leading-tight max-w-xl">
                Satu tusuk, satu cerita rasa yang <span class="text-[#8CC63F]">bikin pulang.</span>
            </h1>

            <p class="mt-5 text-sm md:text-base text-stone-300 max-w-md leading-relaxed">
                Daging ayam pilihan yang dimarinasi rempah rahasia, dibakar langsung di atas bara, disajikan bersama sambal rawit segar dan perasan jeruk nipis.
            </p>

            <div class="mt-8 flex flex-wrap items-center gap-3">
                <a href="{{ auth()->check() ? route('pelanggan.menu') : route('login') }}"
                   class="px-6 py-3 rounded-lg bg-[#E0432D] hover:bg-[#c83723] text-white text-sm font-semibold transition shadow-lg">
                    Pesan Sekarang
                </a>
                <a href="#menu"
                   class="px-6 py-3 rounded-lg border border-stone-500 hover:border-white text-white text-sm font-semibold transition">
                    Lihat Menu Detail
                </a>
            </div>

            <!-- Statistik -->
            <div class="mt-14 pt-8 border-t border-white/15 grid grid-cols-3 max-w-md gap-6">
                <div>
                    <div class="text-xl font-bold">4,9 / 5</div>
                    <div class="text-xs text-stone-400 mt-0.5">Rating Pelanggan</div>
                </div>
                <div>
                    <div class="text-xl font-bold">15 menit</div>
                    <div class="text-xs text-stone-400 mt-0.5">Siap Saji Hangat</div>
                </div>
                <div>
                    <div class="text-xl font-bold">100%</div>
                    <div class="text-xs text-stone-400 mt-0.5">Ayam Segar Pilihan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- MENU FAVORIT -->
    <section id="menu" class="py-20 bg-[#FDF8F2]">
        <div class="max-w-6xl mx-auto px-6">
            <span class="inline-block px-3 py-1 rounded-full bg-[#E0432D]/10 text-[#E0432D] text-[11px] font-bold uppercase tracking-wider">
                Pilihan Utama
            </span>
            <div class="mt-3 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="font-serif-display text-3xl md:text-4xl font-bold">Menu favorit yang selalu dicari</h2>
                    <p class="text-sm text-stone-500 mt-2">Dibakar langsung saat pesanan masuk untuk menjaga cita rasa juicy dan smoky.</p>
                </div>
                <a href="{{ auth()->check() ? route('pelanggan.menu') : route('login') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg border border-stone-300 text-xs font-semibold hover:bg-stone-100 transition self-start">
                    Lihat Semua Menu →
                </a>
            </div>

            <div class="mt-10 grid md:grid-cols-3 gap-6">
                @forelse($favoriteMenus as $menu)
                <div class="bg-white rounded-2xl border border-stone-200/80 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
                    <div class="h-48 bg-stone-200 overflow-hidden">
                        <img src="{{ $menu->image ? asset('storage/' . $menu->image) : 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?auto=format&fit=crop&w=800&q=80' }}"
                             alt="{{ $menu->name }}"
                             class="w-full h-full object-cover hover:scale-105 transition duration-300">
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#E0432D]">{{ $menu->category }}</span>
                            <h3 class="font-bold text-lg mt-1">{{ $menu->name }}</h3>
                            <p class="text-xs text-stone-500 mt-1.5 line-clamp-2">{{ $menu->description }}</p>
                        </div>
                        <div class="mt-5 pt-4 border-t border-stone-100 flex items-center justify-between">
                            <span class="font-bold text-[#E0432D]">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                            <a href="{{ auth()->check() ? route('pelanggan.menu') : route('login') }}"
                               class="w-8 h-8 rounded-lg bg-[#E0432D] text-white flex items-center justify-center font-bold hover:bg-[#c83723] transition">
                                +
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-stone-500 col-span-3">Belum ada menu tersedia.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- KEUNGGULAN (DARK SECTION) -->
    <section id="tentang" class="py-20 bg-[#1A1816] text-white">
        <div class="max-w-6xl mx-auto px-6">
            <span class="inline-block px-3 py-1 rounded-full bg-[#E0432D]/20 text-[#E0432D] text-[11px] font-bold uppercase tracking-wider">
                Kenapa Kami
            </span>
            <h2 class="font-serif-display text-3xl md:text-4xl font-bold mt-3">Sederhana bahannya. Serius rasanya.</h2>
            <p class="text-sm text-stone-400 mt-2">Tidak banyak campuran aneh—hanya bahan segar dan teknik panggangan yang tepat.</p>

            <div class="mt-10 space-y-4">
                <div class="p-5 rounded-xl bg-[#24211E] border border-white/5 flex items-start gap-4">
                    <span class="text-[#8CC63F] text-lg">🔥</span>
                    <div>
                        <h3 class="font-bold text-sm">Bara Asli</h3>
                        <p class="text-xs text-stone-400 mt-1">Dipanggang di atas arang batok pilihan sehingga menghasilkan aroma smoky yang khas.</p>
                    </div>
                </div>
                <div class="p-5 rounded-xl bg-[#24211E] border border-white/5 flex items-start gap-4">
                    <span class="text-[#8CC63F] text-lg">🌶️</span>
                    <div>
                        <h3 class="font-bold text-sm">Sambal Ulek Dadakan</h3>
                        <p class="text-xs text-stone-400 mt-1">Cabai rawit merah segar diulek setiap pagi tanpa bahan pengawet maupun pewarna buatan.</p>
                    </div>
                </div>
                <div class="p-5 rounded-xl bg-[#24211E] border border-white/5 flex items-start gap-4">
                    <span class="text-[#8CC63F] text-lg">🍗</span>
                    <div>
                        <h3 class="font-bold text-sm">Potongan Ayam Fresh</h3>
                        <p class="text-xs text-stone-400 mt-1">Dada dan kulit ayam segar harian yang dimarinasi minimal 6 jam agar bumbu meresap sempurna.</p>
                    </div>
                </div>
                <div class="p-5 rounded-xl bg-[#24211E] border border-white/5 flex items-start gap-4">
                    <span class="text-[#8CC63F] text-lg">⚡</span>
                    <div>
                        <h3 class="font-bold text-sm">Booking & Bayar Cepat</h3>
                        <p class="text-xs text-stone-400 mt-1">Pesan meja langsung dari website dan bayar praktis menggunakan QRIS, E-Wallet, atau Virtual Account.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PROMO PAKET PARTY -->
    <section class="py-20 bg-[#FDF8F2]">
        <div class="max-w-6xl mx-auto px-6">
            <span class="inline-block px-3 py-1 rounded-full bg-[#E0432D]/10 text-[#E0432D] text-[11px] font-bold uppercase tracking-wider">
                Paket Hemat Rame-Rame
            </span>
            <h2 class="font-serif-display text-3xl md:text-4xl font-bold mt-3">Paket Thaichan Party</h2>
            <p class="text-sm text-stone-500 mt-2">30 tusuk sate campur, 3 porsi lontong, ekstra sambal rawit, dan 3 es teh manis segar.</p>

            <div class="mt-4">
                <span class="text-2xl font-extrabold text-[#E0432D]">Rp 115.000</span>
                <span class="block text-xs text-stone-400">Hemat hingga Rp 20.000</span>
            </div>

            <a href="{{ auth()->check() ? route('pelanggan.menu') : route('login') }}"
               class="inline-block mt-5 px-6 py-2.5 rounded-lg bg-[#E0432D] text-white text-xs font-semibold hover:bg-[#c83723] transition">
                Pesan Paket Ini
            </a>

            <div class="mt-8 rounded-2xl overflow-hidden shadow-md h-72 md:h-96">
                <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1400&q=80"
                     alt="Paket Thaichan Party"
                     class="w-full h-full object-cover">
            </div>
        </div>
    </section>

    <!-- 3 LANGKAH PEMESANAN -->
    <section class="py-16 bg-[#F7EFE6]">
        <div class="max-w-6xl mx-auto px-6">
            <span class="inline-block px-3 py-1 rounded-full bg-[#E0432D]/10 text-[#E0432D] text-[11px] font-bold uppercase tracking-wider">
                Cara Pesan
            </span>
            <h2 class="font-serif-display text-3xl font-bold mt-3">Tiga langkah menuju sate hangat</h2>

            <div class="mt-8 grid md:grid-cols-3 gap-6">
                <div class="bg-[#FDF8F2] p-6 rounded-2xl border border-stone-200/80">
                    <span class="font-serif-display text-3xl font-extrabold text-[#E0432D]">01</span>
                    <h3 class="font-bold text-base mt-3">Pilih Menu & Meja</h3>
                    <p class="text-xs text-stone-500 mt-1.5 leading-relaxed">Tentukan jumlah tusuk sate favoritmu serta pilih nomor meja yang masih tersedia.</p>
                </div>
                <div class="bg-[#FDF8F2] p-6 rounded-2xl border border-stone-200/80">
                    <span class="font-serif-display text-3xl font-extrabold text-[#E0432D]">02</span>
                    <h3 class="font-bold text-base mt-3">Bayar Instan</h3>
                    <p class="text-xs text-stone-500 mt-1.5 leading-relaxed">Selesaikan pembayaran dengan aman via QRIS, GoPay, Dana, OVO, atau Transfer Bank.</p>
                </div>
                <div class="bg-[#FDF8F2] p-6 rounded-2xl border border-stone-200/80">
                    <span class="font-serif-display text-3xl font-extrabold text-[#E0432D]">03</span>
                    <h3 class="font-bold text-base mt-3">Kami Bakar Hangat</h3>
                    <p class="text-xs text-stone-500 mt-1.5 leading-relaxed">Pesanan langsung dikonfirmasi kasir dan dibakar hangat saat kamu tiba.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONI -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <span class="inline-block px-3 py-1 rounded-full bg-[#E0432D]/10 text-[#E0432D] text-[11px] font-bold uppercase tracking-wider">
                Kata Mereka
            </span>
            <h2 class="font-serif-display text-3xl font-bold mt-3">Cinta pada gigitan pertama</h2>

            <div class="mt-8 grid md:grid-cols-3 gap-6">
                <div class="p-6 rounded-2xl bg-[#FDF8F2] border border-stone-200/70">
                    <div class="text-amber-400 text-xs">★★★★★</div>
                    <p class="text-sm font-medium mt-3">"Juicy banget daging dadanya, sambalnya nampol tapi nagih!"</p>
                    <p class="text-xs text-[#E0432D] font-semibold mt-4">Raka Pratama · Pelanggan Setia</p>
                </div>
                <div class="p-6 rounded-2xl bg-[#FDF8F2] border border-stone-200/70">
                    <div class="text-amber-400 text-xs">★★★★★</div>
                    <p class="text-sm font-medium mt-3">"Kulitnya garing di luar lembut di dalam, fitur booking mejanya ngebantu banget pas weekend."</p>
                    <p class="text-xs text-[#E0432D] font-semibold mt-4">Nadia Putri · Pelanggan Dine-in</p>
                </div>
                <div class="p-6 rounded-2xl bg-[#FDF8F2] border border-stone-200/70">
                    <div class="text-amber-400 text-xs">★★★★★</div>
                    <p class="text-sm font-medium mt-3">"Masuk daftar sate taichan wajib di Jakarta. Pesen tinggal scan QRIS langsung diproses."</p>
                    <p class="text-xs text-[#E0432D] font-semibold mt-4">Dimas A. · Food Enthusiast</p>
                </div>
            </div>
        </div>
    </section>

    <!-- LOKASI & JAM BUKA -->
    <section id="lokasi" class="py-20 bg-[#1A1816] text-white">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">
            <div class="rounded-2xl overflow-hidden border border-white/10 h-64 bg-[#24211E] flex items-center justify-center">
                <iframe class="w-full h-full grayscale contrast-125"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.666427009756!2d106.845599!3d-6.175392!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTAnMzEuNCJTIDEwNsKwNTAnNDQuMiJF!5e0!3m2!1sid!2sid!4v1700000000000"
                    loading="lazy"></iframe>
            </div>

            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-[#E0432D]/20 text-[#E0432D] text-[11px] font-bold uppercase tracking-wider">
                    Kunjungi Kedai
                </span>
                <h2 class="font-serif-display text-3xl font-bold mt-3">Hangat dari panggangan, setiap hari</h2>
                <p class="text-xs text-stone-400 mt-3 leading-relaxed">
                    Jl. Kemayoran Gempol No. 21, Jakarta Pusat<br>
                    Senin – Jumat: 16.00 – 23.30 WIB<br>
                    Sabtu – Minggu: 15.00 – 00.30 WIB
                </p>
                <a href="{{ auth()->check() ? route('pelanggan.menu') : route('login') }}"
                   class="inline-block mt-6 px-5 py-2.5 rounded-lg bg-[#E0432D] text-white text-xs font-semibold hover:bg-[#c83723] transition">
                    Booking Meja Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- CTA BANNER -->
    <section class="py-16 bg-[#E0432D] text-white text-center px-6">
        <p class="text-xs uppercase tracking-widest opacity-90">🔥 Sate Thaichan Oks</p>
        <h2 class="font-serif-display text-3xl md:text-4xl font-extrabold mt-2">Lapar nggak perlu ditunda.</h2>
        <p class="text-xs md:text-sm opacity-90 mt-2">Pilih menu, amankan mejamu, dan biarkan kami menyiapkan sate hangat untukmu.</p>
        <a href="{{ auth()->check() ? route('pelanggan.menu') : route('register') }}"
           class="inline-block mt-6 px-6 py-3 rounded-lg bg-white text-[#E0432D] font-bold text-xs shadow hover:bg-stone-100 transition">
            Mulai Pesan Sekarang
        </a>
    </section>

    <!-- FOOTER -->
    <footer class="bg-[#141210] text-stone-400 py-12 px-6 text-xs">
        <div class="max-w-6xl mx-auto grid md:grid-cols-4 gap-8 pb-8 border-b border-white/10">
            <div class="md:col-span-2">
                <div class="flex items-center gap-2 text-white font-bold text-sm">
                    <span class="w-6 h-6 rounded bg-[#E0432D] flex items-center justify-center text-xs">🔥</span>
                    <span>Sate Thaichan Oks</span>
                </div>
                <p class="mt-3 max-w-xs leading-relaxed">Sate taichan bakar bara asli dengan sambal rawit segar dan sistem pemesanan online cepat.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-2">Navigasi</h4>
                <ul class="space-y-1.5">
                    <li><a href="#beranda" class="hover:text-white">Beranda</a></li>
                    <li><a href="#menu" class="hover:text-white">Menu Favorit</a></li>
                    <li><a href="#lokasi" class="hover:text-white">Lokasi Kedai</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-2">Akses Akun</h4>
                <ul class="space-y-1.5">
                    <li><a href="{{ route('login') }}" class="hover:text-white">Login Pelanggan / Staf</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white">Daftar Pelanggan</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-6xl mx-auto pt-6 text-stone-500">
            © {{ date('Y') }} Sate Thaichan Oks. All rights reserved.
        </div>
    </footer>

</body>
</html>