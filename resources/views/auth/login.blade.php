<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Panen Desa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-xl max-w-sm w-full border border-gray-100">
        <h2 class="text-3xl font-black text-gray-900 mb-6 text-center">MASUK</h2>

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r">
                <p class="font-bold">Gagal!</p>
                <p>{{ $errors->first() }}</p>
            </div>
        @endif

        <form action="/login" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-gray-900 text-lg font-bold mb-2">Nomor HP</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="08..." 
                       class="w-full px-4 py-4 text-xl border-2 border-gray-300 rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all">
            </div>

            <div>
                <label class="block text-gray-900 text-lg font-bold mb-2">PIN Rahasia</label>
                <input type="password" name="password" required placeholder="••••" 
                       class="w-full px-4 py-4 text-2xl tracking-[0.5em] border-2 border-gray-300 rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all">
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-4 rounded-xl text-xl transition-all shadow-lg active:scale-95 mt-4">
                MASUK
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="/" class="text-gray-500 hover:text-green-600 font-medium">← Kembali ke Halaman Utama</a>
        </div>
    </div>

</body>
</html>