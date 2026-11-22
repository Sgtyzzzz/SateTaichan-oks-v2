<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email — Sate Thaichan Oks</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#FDF8F2] text-[#1A1816] min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md bg-white rounded-3xl border border-stone-200/80 shadow-md p-8 text-center space-y-5">
        <div class="w-14 h-14 mx-auto rounded-2xl bg-[#E0432D]/10 text-[#E0432D] flex items-center justify-center text-2xl">
            ✉️
        </div>

        <div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-[#E0432D]">Satu Langkah Lagi</span>
            <h1 class="font-serif-display text-2xl font-extrabold mt-1">Cek Inbox Email Kamu</h1>
            <p class="text-xs text-stone-500 mt-2 leading-relaxed">
                Kami telah mengirimkan link verifikasi ke alamat email <strong class="text-[#1A1816]">{{ auth()->user()->email }}</strong>. Klik link tersebut untuk mengaktifkan akun kamu.
            </p>
        </div>

        @if (session('message'))
            <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
                ✅ {{ session('message') }}
            </div>
        @endif

        <div class="pt-2 space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full py-3 rounded-xl bg-[#E0432D] hover:bg-[#c83723] text-white font-semibold text-xs transition cursor-pointer">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2.5 rounded-xl border border-stone-200 hover:bg-stone-100 text-stone-600 font-semibold text-xs transition cursor-pointer">
                    Keluar / Ganti Akun
                </button>
            </form>
        </div>

        <p class="text-[11px] text-stone-400 pt-2">
            Tidak ketemu di kotak masuk? Coba cek folder <strong>Spam</strong> atau <strong>Promosi</strong>.
        </p>
    </div>

</body>
</html>