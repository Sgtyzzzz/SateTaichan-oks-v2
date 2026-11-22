<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Console — Sate Thaichan Oks</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FDF8F2] text-[#1A1816] min-h-screen flex">

    <!-- SIDEBAR KIRI -->
    <aside class="w-64 bg-[#181614] text-white flex flex-col justify-between p-5 shrink-0 min-h-screen sticky top-0">
        <div class="space-y-8">
            <div class="flex items-center gap-3 px-2">
                <span class="w-9 h-9 rounded-xl bg-[#E0432D] flex items-center justify-center text-base">🔥</span>
                <div>
                    <div class="font-bold text-sm leading-tight">Sate Thaichan Oks</div>
                    <div class="text-[10px] tracking-widest text-stone-400 uppercase">KASIR CONSOLE</div>
                </div>
            </div>

            <nav class="space-y-1.5 text-xs font-semibold">
                <button type="button" onclick="switchTab('antrean')" id="nav-antrean"
                        class="w-full flex items-center justify-between px-3.5 py-3 rounded-xl bg-white/10 text-white cursor-pointer">
                    <span>🧾 Pesanan Online</span>
                    <span class="w-2 h-2 rounded-full bg-[#E0432D]"></span>
                </button>
                <button type="button" onclick="switchTab('offline')" id="nav-offline"
                        class="w-full flex items-center gap-2.5 px-3.5 py-3 rounded-xl text-stone-400 hover:text-white hover:bg-white/5 cursor-pointer">
                    <span>➕ Buat Pesanan</span>
                </button>
                <button type="button" onclick="switchTab('meja')" id="nav-meja"
                        class="w-full flex items-center gap-2.5 px-3.5 py-3 rounded-xl text-stone-400 hover:text-white hover:bg-white/5 cursor-pointer">
                    <span>🪑 Status Meja</span>
                </button>
            </nav>
        </div>

        <div class="pt-4 border-t border-white/10 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-stone-700 flex items-center justify-center text-xs font-bold uppercase">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="text-xs font-bold">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-stone-400">Kasir Shift Aktif</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" title="Keluar" class="p-2 rounded-lg bg-[#E0432D]/20 text-[#E0432D] hover:bg-[#E0432D] hover:text-white transition text-xs cursor-pointer">
                    🚪
                </button>
            </form>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 p-8 max-w-6xl mx-auto space-y-6">
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- TAB 1: PESANAN ONLINE MASUK -->
        <div id="tab-antrean" class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="font-serif-display text-3xl font-extrabold">Pesanan Online Masuk</h1>
                    <p class="text-xs text-stone-500 mt-1">Kelola pesanan yang masuk dari website & antrean dapur.</p>
                </div>
                <a href="{{ route('kasir.dashboard') }}" class="px-4 py-2.5 rounded-xl bg-white border border-stone-200 hover:bg-stone-50 text-xs font-semibold text-sky-600 shadow-sm">
                    🔄 Sync Pesanan
                </a>
            </div>

            <!-- 3 KARTU STATISTIK ANTREAN -->
            <div class="grid md:grid-cols-3 gap-5">
                <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">PESANAN BARU</p>
                        <p class="font-serif-display text-3xl font-extrabold mt-1">{{ $countBaru }}</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-red-50 text-[#E0432D] text-[10px] font-bold">Live Update</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">SEDANG DIPROSES</p>
                        <p class="font-serif-display text-3xl font-extrabold mt-1">{{ $countProses }}</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold">Sedang dimasak</span>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">SIAP DIANTAR</p>
                        <p class="font-serif-display text-3xl font-extrabold mt-1">{{ $countSiap }}</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold">Siap saji</span>
                </div>
            </div>

            <!-- DAFTAR ANTREAN AKTIF -->
            <div class="space-y-3">
                <h2 class="text-xs font-bold text-stone-700">Daftar Antrean Pesanan Aktif</h2>

                @forelse($orders->whereNotIn('order_status', ['completed', 'cancelled']) as $order)
                <div class="bg-white p-5 rounded-2xl border border-stone-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1.5">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-extrabold text-[#E0432D]">#{{ $order->order_code }}</span>

                            @if($order->payment_status === 'paid')
                                <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 text-[10px] font-bold">Lunas ({{ strtoupper($order->payment_method) }})</span>
                            @else
                                <span class="px-2 py-0.5 rounded-md bg-stone-100 text-stone-600 text-[10px] font-bold">Belum Bayar</span>
                            @endif

                            <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 text-[10px] font-bold">
                                {{ $order->order_type === 'dine_in' ? ($order->table->table_number ?? 'Dine-in') : 'Take Away' }}
                            </span>

                            <span class="text-[11px] text-stone-400">• {{ $order->created_at->diffForHumans() }}</span>
                        </div>

                        <div class="font-bold text-sm">
                            {{ $order->customer_name ?? ($order->user->name ?? 'Pelanggan') }}
                            <span class="text-xs font-normal text-stone-500">({{ $order->spicy_level }})</span>
                        </div>

                        <p class="text-xs text-stone-500">
                            @foreach($order->items as $item)
                                {{ $item->quantity }}x {{ $item->menu->name ?? 'Menu' }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </p>

                        <div class="pt-1">
                            <span class="text-[10px] text-stone-400 block">Total Pembayaran</span>
                            <span class="font-extrabold text-sm">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- TOMBOL AKSI CEPAT KASIR -->
                    <div class="flex flex-wrap items-center gap-2">
                        @if($order->payment_status !== 'paid')
                            <form action="{{ route('kasir.orders.status', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="payment_status" value="paid">
                                <input type="hidden" name="order_status" value="cooking">
                                <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold cursor-pointer">
                                    ✓ Konfirmasi Bayar & Masak
                                </button>
                            </form>
                        @elseif($order->order_status === 'pending' || $order->order_status === 'cooking')
                            <form action="{{ route('kasir.orders.status', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="order_status" value="ready">
                                <button type="submit" class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold cursor-pointer">
                                    🍽️ Tandai Siap Diantar
                                </button>
                            </form>
                        @elseif($order->order_status === 'ready')
                            <form action="{{ route('kasir.orders.status', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="order_status" value="completed">
                                <button type="submit" class="px-4 py-2 rounded-xl bg-[#181614] hover:bg-stone-800 text-white text-xs font-semibold cursor-pointer">
                                    ✅ Pesanan Selesai
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="bg-white p-10 rounded-2xl border border-stone-200 text-center text-xs text-stone-400">
                    Belum ada antrean pesanan aktif saat ini.
                </div>
                @endforelse
            </div>
        </div>

        <!-- TAB 2: BUAT PESANAN OFFLINE (POS WALK-IN) -->
        <div id="tab-offline" class="hidden space-y-6">
            <div>
                <h1 class="font-serif-display text-3xl font-extrabold">Buat Pesanan Walk-in (Kasir)</h1>
                <p class="text-xs text-stone-500 mt-1">Input pesanan pelanggan yang datang langsung ke kasir.</p>
            </div>

            <form action="{{ route('kasir.orders.offline') }}" method="POST" class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-5 text-xs">
                @csrf
                <div class="grid md:grid-cols-4 gap-4">
                    <div>
                        <label class="block font-semibold mb-1">Nama Pelanggan</label>
                        <input type="text" name="customer_name" placeholder="Opsional (Walk-in)" class="w-full px-3 py-2.5 rounded-xl border border-stone-300">
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Tipe Pesanan</label>
                        <select name="order_type" class="w-full px-3 py-2.5 rounded-xl border border-stone-300">
                            <option value="dine_in">Dine-in (Makan di Tempat)</option>
                            <option value="take_away">Take Away (Bungkus)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Pilih Meja</label>
                        <select name="table_id" class="w-full px-3 py-2.5 rounded-xl border border-stone-300">
                            <option value="">-- Tanpa Meja --</option>
                            @foreach($tables->where('status', 'available') as $tbl)
                                <option value="{{ $tbl->id }}">{{ $tbl->table_number }} ({{ $tbl->capacity }} Kursi)</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Metode Bayar</label>
                        <select name="payment_method" class="w-full px-3 py-2.5 rounded-xl border border-stone-300">
                            <option value="cash">Tunai / Cash</option>
                            <option value="qris">QRIS</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block font-semibold">Pilih Menu & Jumlah Porsi</label>
                    <div class="grid md:grid-cols-3 gap-3">
                        @foreach($menus as $idx => $menu)
                        <div class="p-3 rounded-xl border border-stone-200 flex items-center justify-between">
                            <div>
                                <div class="font-bold">{{ $menu->name }}</div>
                                <div class="text-stone-500">Rp {{ number_format($menu->price, 0, ',', '.') }} (Stok: {{ $menu->stock }})</div>
                            </div>
                            <input type="hidden" name="items[{{ $idx }}][menu_id]" value="{{ $menu->id }}" disabled id="menu-id-{{ $idx }}">
                            <input type="number" min="0" max="{{ $menu->stock }}" value="0"
                                   onchange="toggleMenuInput({{ $idx }}, this.value)"
                                   name="items[{{ $idx }}][quantity]" disabled id="menu-qty-{{ $idx }}"
                                   class="w-16 px-2 py-1.5 rounded-lg border border-stone-300 text-center font-bold">
                        </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="px-6 py-3 rounded-xl bg-[#E0432D] hover:bg-[#c83723] text-white font-semibold cursor-pointer">
                    Simpan & Kirim ke Dapur
                </button>
            </form>
        </div>

        <!-- TAB 3: KELOLA STATUS MEJA -->
        <div id="tab-meja" class="hidden space-y-6">
            <div>
                <h1 class="font-serif-display text-3xl font-extrabold">Monitoring Meja Outlet</h1>
                <p class="text-xs text-stone-500 mt-1">Ubah status meja secara manual jika pelanggan sudah selesai makan.</p>
            </div>

            <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                @foreach($tables as $table)
                <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-sm space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-sm">{{ $table->table_number }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold
                            {{ $table->status === 'available' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            {{ strtoupper($table->status) }}
                        </span>
                    </div>
                    <p class="text-stone-500">Kapasitas: {{ $table->capacity }} Orang</p>
                    <form action="{{ route('kasir.tables.status', $table->id) }}" method="POST" class="flex gap-1.5">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="w-full px-2 py-1.5 rounded-lg border border-stone-300">
                            <option value="available" {{ $table->status === 'available' ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ $table->status === 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="reserved" {{ $table->status === 'reserved' ? 'selected' : '' }}>Reserved</option>
                        </select>
                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-[#181614] text-white font-semibold cursor-pointer">Set</button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
    </main>

    <script>
        function switchTab(tab) {
            ['antrean', 'offline', 'meja'].forEach(t => {
                document.getElementById('tab-' + t).classList.toggle('hidden', t !== tab);
                const nav = document.getElementById('nav-' + t);
                nav.className = t === tab
                    ? "w-full flex items-center justify-between px-3.5 py-3 rounded-xl bg-white/10 text-white cursor-pointer"
                    : "w-full flex items-center gap-2.5 px-3.5 py-3 rounded-xl text-stone-400 hover:text-white hover:bg-white/5 cursor-pointer";
            });
        }

        // Aktifkan input quantity saat kasir mengubah angka > 0
        document.querySelectorAll('[id^="menu-qty-"]').forEach((input, idx) => {
            input.disabled = false;
            input.addEventListener('input', () => {
                const idInput = document.getElementById('menu-id-' + idx);
                if (parseInt(input.value) > 0) {
                    idInput.disabled = false;
                    input.disabled = false;
                } else {
                    idInput.disabled = true;
                }
            });
        });
    </script>
</body>
</html>