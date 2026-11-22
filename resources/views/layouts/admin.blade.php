<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Console Admin') — Sate Thaichan Oks</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FDF8F2] text-[#1A1816] min-h-screen flex">

    <!-- SIDEBAR ADMIN KIRI -->
    <aside class="w-64 bg-[#181614] text-white flex flex-col justify-between p-5 shrink-0 min-h-screen sticky top-0">
        <div class="space-y-8">
            <div class="flex items-center gap-3 px-2">
                <span class="w-9 h-9 rounded-xl bg-[#E0432D] flex items-center justify-center text-base">🔥</span>
                <div>
                    <div class="font-bold text-sm leading-tight">Sate Thaichan Oks</div>
                    <div class="text-[10px] tracking-widest text-amber-400 font-bold uppercase">CONSOLE ADMIN</div>
                </div>
            </div>

            @php
                $navs = [
                    ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => '📊', 'pattern' => 'admin.dashboard'],
                    ['route' => 'admin.pesanan', 'label' => 'Pesanan', 'icon' => '🧾', 'pattern' => 'admin.pesanan'],
                    ['route' => 'admin.menu', 'label' => 'Menu', 'icon' => '🍢', 'pattern' => 'admin.menu*'],
                    ['route' => 'admin.kursi', 'label' => 'Manajemen Kursi', 'icon' => '🪑', 'pattern' => 'admin.kursi'],
                    ['route' => 'admin.laporan', 'label' => 'Laporan', 'icon' => '📈', 'pattern' => 'admin.laporan'],
                    ['route' => 'admin.kasir', 'label' => 'Akun Kasir', 'icon' => '👥', 'pattern' => 'admin.kasir'],
                ];
            @endphp

            <nav class="space-y-1.5 text-xs font-semibold">
                @foreach($navs as $nav)
                    @php $active = request()->routeIs($nav['pattern']); @endphp
                    <a href="{{ route($nav['route']) }}"
                       class="flex items-center justify-between px-3.5 py-3 rounded-xl transition {{ $active ? 'bg-white/10 text-white' : 'text-stone-400 hover:text-white hover:bg-white/5' }}">
                        <span class="flex items-center gap-2.5">
                            <span>{{ $nav['icon'] }}</span>
                            <span>{{ $nav['label'] }}</span>
                        </span>
                        @if($active)
                            <span class="w-1.5 h-4 rounded-full bg-[#E0432D]"></span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="pt-4 border-t border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-[#E0432D] flex items-center justify-center text-xs font-bold uppercase">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="text-xs font-bold">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-stone-400">Owner & Operator</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" title="Keluar" class="p-2 rounded-lg text-stone-400 hover:text-[#E0432D] transition text-xs cursor-pointer">
                    🚪
                </button>
            </form>
        </div>
    </aside>

    <!-- KONTEN KANAN -->
    <main class="flex-1 p-8 max-w-7xl mx-auto space-y-6">
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>