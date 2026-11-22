<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Pembayaran — Sate Thaichan Oks</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FDF8F2] text-[#1A1816] min-h-screen">

    <header class="bg-[#FDF8F2] border-b border-stone-200 px-6 h-16 flex items-center justify-between max-w-6xl mx-auto">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 font-bold text-sm">
            <span class="w-8 h-8 rounded-lg bg-[#E0432D] text-white flex items-center justify-center">🔥</span>
            <span>Sate Thaichan Oks</span>
        </a>
        <span class="text-xs font-semibold text-stone-500">Order #{{ $order->order_code }}</span>
    </header>

    <form action="{{ route('pelanggan.payment.confirm', $order->id) }}" method="POST" class="max-w-5xl mx-auto px-6 py-10 grid md:grid-cols-3 gap-8 items-start">
        @csrf
        <!-- KIRI: METODE PEMBAYARAN -->
        <div class="md:col-span-2 space-y-6">
            <a href="{{ route('pelanggan.menu') }}" class="text-xs text-stone-500 hover:text-stone-900">← Kembali ke Menu</a>
            <h1 class="font-serif-display text-3xl font-extrabold">Selesaikan Pembayaran</h1>

            <div class="p-4 rounded-2xl bg-white border border-stone-200 text-xs">
                <span class="text-[10px] font-bold uppercase text-stone-400">Detail Layanan</span>
                <p class="font-bold mt-1">
                    {{ $order->order_type === 'dine_in' ? 'Makan di Tempat — ' . ($order->table->table_number ?? '') : 'Take Away (Bawa Pulang)' }}
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-white border border-stone-200 space-y-5">
                <div>
                    <span class="px-2.5 py-0.5 rounded-full bg-[#E0432D]/10 text-[#E0432D] text-[10px] font-bold uppercase">Metode</span>
                    <h2 class="font-serif-display text-xl font-bold mt-1">Pilih Metode Pembayaran</h2>
                </div>

                <div>
                    <p class="text-[11px] font-bold uppercase text-stone-400 mb-2">Dompet Digital (E-Wallet)</p>
                    <div class="grid sm:grid-cols-2 gap-3 text-xs">
                        @foreach(['GoPay' => 'Instan & Bebas Biaya', 'OVO' => 'Konfirmasi Otomatis', 'Dana' => 'Praktis & Aman', 'ShopeePay' => 'Bayar Cepat via QR'] as $method => $desc)
                        <label class="p-3.5 rounded-xl border border-stone-200 flex items-center gap-3 cursor-pointer has-[:checked]:border-[#E0432D] has-[:checked]:bg-[#E0432D]/5">
                            <input type="radio" name="payment_method" value="{{ $method }}" {{ $loop->first ? 'checked' : '' }} class="accent-[#E0432D]">
                            <div>
                                <div class="font-bold">{{ $method }}</div>
                                <div class="text-[11px] text-stone-400">{{ $desc }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="text-[11px] font-bold uppercase text-stone-400 mb-2">Transfer Bank (Virtual Account)</p>
                    <div class="grid sm:grid-cols-2 gap-3 text-xs">
                        @foreach(['BCA Virtual Account' => 'Verifikasi otomatis 24 jam', 'Mandiri Bill Payment' => 'Konfirmasi instan'] as $method => $desc)
                        <label class="p-3.5 rounded-xl border border-stone-200 flex items-center gap-3 cursor-pointer has-[:checked]:border-[#E0432D] has-[:checked]:bg-[#E0432D]/5">
                            <input type="radio" name="payment_method" value="{{ $method }}" class="accent-[#E0432D]">
                            <div>
                                <div class="font-bold">{{ $method }}</div>
                                <div class="text-[11px] text-stone-400">{{ $desc }}</div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="text-[11px] font-bold uppercase text-stone-400 mb-2">Lainnya</p>
                    <label class="p-3.5 rounded-xl border border-stone-200 flex items-center gap-3 cursor-pointer text-xs has-[:checked]:border-[#E0432D] has-[:checked]:bg-[#E0432D]/5">
                        <input type="radio" name="payment_method" value="Bayar di Kasir (Tunai)" class="accent-[#E0432D]">
                        <div>
                            <div class="font-bold">Bayar di Kasir (Tunai / QRIS Meja Kasir)</div>
                            <div class="text-[11px] text-stone-400">Konfirmasi langsung ke kasir saat tiba</div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- KANAN: RINGKASAN PESANAN -->
        <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-serif-display text-lg font-bold">Ringkasan Pesanan</h2>
                <span class="px-2.5 py-0.5 rounded-full bg-[#E0432D] text-white text-[10px] font-bold">{{ $order->items->sum('quantity') }} Item</span>
            </div>

            <div class="divide-y divide-stone-100 text-xs">
                @foreach($order->items as $item)
                <div class="py-2.5 flex justify-between">
                    <div>
                        <div class="font-bold">{{ $item->menu->name }}</div>
                        <div class="text-[11px] text-stone-400">{{ $item->spicy_level }}</div>
                    </div>
                    <div class="text-right">
                        <span class="text-stone-400 mr-2">{{ $item->quantity }}x</span>
                        <span class="font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="border-t border-stone-100 pt-3 space-y-1.5 text-xs">
                <div class="flex justify-between text-stone-500">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-stone-500">
                    <span>Biaya Layanan</span>
                    <span>Rp {{ number_format($order->service_fee, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-stone-100">
                    <span class="font-bold">Total Pembayaran</span>
                    <span class="font-extrabold text-xl text-[#E0432D]">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl bg-[#E0432D] hover:bg-[#c83723] text-white font-bold text-xs transition shadow cursor-pointer">
                Bayar Sekarang • Rp {{ number_format($order->total_price, 0, ',', '.') }}
            </button>
        </div>
    </form>
</body>
</html>