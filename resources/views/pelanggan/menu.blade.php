<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu & Pilih Meja — Sate Thaichan Oks</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FDF8F2] text-[#1A1816]">

    <!-- NAVBAR -->
    <header class="sticky top-0 z-40 bg-[#FDF8F2]/95 backdrop-blur border-b border-stone-200">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-bold text-sm">
                <span class="w-8 h-8 rounded-lg bg-[#E0432D] text-white flex items-center justify-center">🔥</span>
                <span>Sate Thaichan Oks</span>
            </a>
            <div class="flex items-center gap-4 text-xs">
                <span class="text-stone-600">Halo, <strong>{{ auth()->user()->name }}</strong></span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-lg border border-stone-300 hover:bg-stone-100 font-semibold cursor-pointer">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <div class="max-w-6xl mx-auto px-6 py-10">
        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- RIWAYAT PESANAN AKTIF -->
        @if($myOrders->count() > 0)
        <div class="mb-10 bg-white p-5 rounded-2xl border border-stone-200/80 shadow-sm">
            <h2 class="font-bold text-sm mb-3">Status Pesanan Terakhir Kamu</h2>
            <div class="grid md:grid-cols-3 gap-3">
                @foreach($myOrders as $ord)
                <div class="p-3.5 rounded-xl bg-[#FDF8F2] border border-stone-200 text-xs flex justify-between items-center">
                    <div>
                        <span class="font-bold text-[#E0432D]">#{{ $ord->order_code }}</span>
                        <p class="text-stone-500 mt-0.5">
                            {{ $ord->order_type === 'dine_in' ? ($ord->table->table_number ?? 'Dine-in') : 'Take Away' }} • Rp {{ number_format($ord->total_price, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-right">
                        @if($ord->payment_status === 'unpaid')
                            <a href="{{ route('pelanggan.payment', $ord->id) }}" class="px-2.5 py-1 rounded bg-[#E0432D] text-white font-semibold">Bayar</a>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                {{ $ord->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $ord->payment_status }} / {{ $ord->order_status }}
                            </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="grid lg:grid-cols-3 gap-8 items-start">
            <!-- KOLOM KIRI: DAFTAR MENU -->
            <div class="lg:col-span-2 space-y-8">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#E0432D]">Pilihan Oks</span>
                    <h1 class="font-serif-display text-3xl font-extrabold mt-1">Mau yang mana hari ini?</h1>
                    <p class="text-xs text-stone-500 mt-1">Dibakar langsung saat dipesan. Pilih menu di bawah untuk masuk ke keranjang.</p>
                </div>

                @foreach($menus->groupBy('category') as $category => $categoryMenus)
                <div>
                    <h2 class="font-bold text-lg mb-4 pb-2 border-b border-stone-200">{{ $category }}</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        @foreach($categoryMenus as $menu)
                        <div class="bg-white rounded-2xl border border-stone-200/80 p-4 flex flex-col justify-between shadow-sm">
                            <div>
                                @if($menu->badge)
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#E0432D]">{{ $menu->badge }}</span>
                                @endif
                                <h3 class="font-bold text-base mt-0.5">{{ $menu->name }}</h3>
                                <p class="text-xs text-stone-500 mt-1">{{ $menu->description }}</p>
                                <p class="text-[11px] text-stone-400 mt-1">Stok: {{ $menu->stock }}</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-stone-100 flex items-center justify-between">
                                <span class="font-bold text-sm text-[#E0432D]">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                            data-id="{{ $menu->id }}"
                                            data-name="{{ $menu->name }}"
                                            data-price="{{ $menu->price }}"
                                            data-delta="-1"
                                            onclick="handleCartBtn(this)"
                                            class="w-7 h-7 rounded-lg border border-stone-300 font-bold text-xs hover:bg-stone-100 cursor-pointer">-</button>
                                    <span id="qty-{{ $menu->id }}" class="text-xs font-bold w-4 text-center">0</span>
                                    <button type="button"
                                            data-id="{{ $menu->id }}"
                                            data-name="{{ $menu->name }}"
                                            data-price="{{ $menu->price }}"
                                            data-delta="1"
                                            onclick="handleCartBtn(this)"
                                            class="w-7 h-7 rounded-lg bg-[#E0432D] text-white font-bold text-xs hover:bg-[#c83723] cursor-pointer">+</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <!-- KOLOM KANAN: KERANJANG, PILIH KURSI & LEVEL PEDAS -->
            <form action="{{ route('pelanggan.checkout') }}" method="POST" class="bg-white p-6 rounded-2xl border border-stone-200 shadow-md sticky top-24 space-y-5">
                @csrf
                <h2 class="font-serif-display text-xl font-bold">Ringkasan Pesanan</h2>

                <!-- Jenis Pesanan -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1.5">Mau makan di mana?</label>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <label class="border border-stone-200 rounded-xl p-2.5 flex items-center gap-2 cursor-pointer has-[:checked]:border-[#E0432D] has-[:checked]:bg-[#E0432D]/5">
                            <input type="radio" name="order_type" value="dine_in" checked onchange="toggleTableSelect(true)" class="accent-[#E0432D]">
                            <span class="font-semibold">Dine-in (Pilih Meja)</span>
                        </label>
                        <label class="border border-stone-200 rounded-xl p-2.5 flex items-center gap-2 cursor-pointer has-[:checked]:border-[#E0432D] has-[:checked]:bg-[#E0432D]/5">
                            <input type="radio" name="order_type" value="take_away" onchange="toggleTableSelect(false)" class="accent-[#E0432D]">
                            <span class="font-semibold">Take Away</span>
                        </label>
                    </div>
                </div>

                <!-- Pilihan Kursi/Meja -->
                <div id="tableSelectBox">
                    <label class="block text-xs font-semibold text-stone-700 mb-1.5">Pilih Nomor Meja</label>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($tables as $table)
                        <label class="text-center p-2 rounded-xl border text-xs cursor-pointer transition
                            {{ $table->status === 'available'
                                ? 'border-stone-200 hover:border-[#E0432D] has-[:checked]:bg-[#E0432D] has-[:checked]:text-white has-[:checked]:border-[#E0432D]'
                                : 'bg-stone-100 border-stone-200 text-stone-400 cursor-not-allowed opacity-60' }}">
                            <input type="radio" name="table_id" value="{{ $table->id }}" class="hidden" {{ $table->status !== 'available' ? 'disabled' : '' }}>
                            <div class="font-bold">{{ str_replace('Meja ', 'M-', $table->table_number) }}</div>
                            <div class="text-[9px]">{{ $table->status === 'available' ? $table->capacity.' org' : 'Terisi' }}</div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Level Pedas -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 mb-1.5">Level Pedas Sambal</label>
                    <select name="spicy_level" class="w-full px-3 py-2.5 rounded-xl border border-stone-200 text-xs bg-white outline-none focus:border-[#E0432D]">
                        <option value="Level 0 - Tanpa Pedas">Level 0 • Tanpa Pedas</option>
                        <option value="Level 1 - Santai">Level 1 • Santai</option>
                        <option value="Level 2 - Sedang" selected>Level 2 • Sedang (Rekomendasi)</option>
                        <option value="Level 3 - Pedas">Level 3 • Pedas</option>
                        <option value="Level 4 - Nampol">Level 4 • Nampol</option>
                    </select>
                </div>

                <!-- List Item Keranjang -->
                <div id="cartItemsContainer" class="border-t border-b border-stone-100 py-3 space-y-2 text-xs min-h-[60px]">
                    <p class="text-stone-400 text-center py-2">Belum ada menu dipilih.</p>
                </div>
                <div id="hiddenInputs"></div>

                <!-- Total -->
                <div class="space-y-1 text-xs">
                    <div class="flex justify-between text-stone-500">
                        <span>Subtotal</span>
                        <span id="subtotalText">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-stone-500">
                        <span>Biaya Layanan</span>
                        <span>Rp 2.000</span>
                    </div>
                    <div class="flex justify-between font-bold text-base pt-2 border-t border-stone-100">
                        <span>Total</span>
                        <span id="totalText" class="text-[#E0432D]">Rp 0</span>
                    </div>
                </div>

                <button type="submit" id="checkoutBtn" disabled
                        class="w-full py-3 rounded-xl bg-[#E0432D] disabled:bg-stone-300 hover:bg-[#c83723] text-white font-semibold text-xs transition cursor-pointer">
                    Lanjut ke Pembayaran →
                </button>
            </form>
        </div>
    </div>

    <script>
        const cart = {};

        function toggleTableSelect(show) {
            document.getElementById('tableSelectBox').style.display = show ? 'block' : 'none';
        }

        function handleCartBtn(btn) {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const price = parseInt(btn.dataset.price);
            const delta = parseInt(btn.dataset.delta);
            updateCart(id, name, price, delta);
        }

        function updateCart(id, name, price, delta) {
            if (!cart[id]) cart[id] = { name, price, qty: 0 };
            cart[id].qty += delta;
            if (cart[id].qty <= 0) delete cart[id];

            document.getElementById('qty-' + id).innerText = cart[id] ? cart[id].qty : 0;
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cartItemsContainer');
            const hidden = document.getElementById('hiddenInputs');
            const keys = Object.keys(cart);

            if (keys.length === 0) {
                container.innerHTML = '<p class="text-stone-400 text-center py-2">Belum ada menu dipilih.</p>';
                hidden.innerHTML = '';
                document.getElementById('subtotalText').innerText = 'Rp 0';
                document.getElementById('totalText').innerText = 'Rp 0';
                document.getElementById('checkoutBtn').disabled = true;
                return;
            }

            let html = '';
            let inputs = '';
            let subtotal = 0;

            keys.forEach((id, index) => {
                const item = cart[id];
                const sub = item.price * item.qty;
                subtotal += sub;
                html += `<div class="flex justify-between"><span>${item.qty}x ${item.name}</span><span class="font-semibold">Rp ${sub.toLocaleString('id-ID')}</span></div>`;
                inputs += `<input type="hidden" name="items[${index}][menu_id]" value="${id}">`;
                inputs += `<input type="hidden" name="items[${index}][quantity]" value="${item.qty}">`;
            });

            container.innerHTML = html;
            hidden.innerHTML = inputs;
            document.getElementById('subtotalText').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('totalText').innerText = 'Rp ' + (subtotal + 2000).toLocaleString('id-ID');
            document.getElementById('checkoutBtn').disabled = false;
        }
    </script>
</body>
</html>