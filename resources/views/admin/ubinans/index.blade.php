<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harvest Estimates - TaniCheck</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900 min-h-screen flex flex-col">

    <header class="h-16 bg-white border-b flex items-center justify-between px-6 shadow-sm flex-shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-green-600 font-bold transition">&larr; Dashboard</a>
            <h1 class="text-xl font-bold text-gray-800 border-l pl-4">Ubinan & Estimasi Panen</h1>
        </div>
    </header>

    <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 lg:p-8">
        
        @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                <ul class="list-disc ml-5 text-sm font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            
            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-1">Input Data Ubinan</h2>
                    <p class="text-xs text-gray-500 mb-6">Sistem akan otomatis menghitung estimasi total panen berdasarkan sampel 2.5m x 2.5m.</p>

                    <form action="{{ route('admin.ubinans.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Pilih Lahan</label>
                            <select name="land_id" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 transition p-2.5 border">
                                <option value="">-- Pilih Lahan --</option>
                                @foreach($lands as $land)
                                    <option value="{{ $land->id }}">{{ $land->nickname }} ({{ $land->user->name }} - {{ $land->area_size }} Ha)</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Berat Sampel (Kg)</label>
                            <input type="number" step="0.01" name="sample_weight_kg" placeholder="Contoh: 5.45" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 transition p-2.5 border">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Rencana Tanggal Panen</label>
                            <input type="date" name="projected_harvest_date" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 transition p-2.5 border">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Catatan Tambahan</label>
                            <textarea name="notes" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 transition p-2.5 border placeholder-gray-400" placeholder="Kondisi cuaca, hama, dll..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg shadow transition">
                            Hitung Estimasi
                        </button>
                    </form>
                </div>
            </div>

            <div class="w-full lg:w-2/3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Daftar Estimasi</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b">
                                    <th class="px-6 py-4 font-bold">Lahan & Petani</th>
                                    <th class="px-6 py-4 font-bold text-right">Sampel (Kg)</th>
                                    <th class="px-6 py-4 font-bold text-right">Estimasi (Kg)</th>
                                    <th class="px-6 py-4 font-bold text-center">Status</th>
                                    <th class="px-6 py-4 font-bold text-center">Update</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-100">
                                @forelse($ubinans as $ubinan)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-gray-800">{{ $ubinan->land->nickname }}</p>
                                        <p class="text-xs text-gray-500">{{ $ubinan->land->user->name }} &bull; Tgl: {{ \Carbon\Carbon::parse($ubinan->projected_harvest_date)->format('d M Y') }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-gray-600">
                                        {{ number_format($ubinan->sample_weight_kg, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-green-700">
                                        {{ number_format($ubinan->estimated_yield_kg, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($ubinan->status === 'pending')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">Pending</span>
                                        @elseif($ubinan->status === 'harvested')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">Selesai</span>
                                        @else
                                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">Gagal</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.ubinans.update-status', $ubinan->id) }}" method="POST" class="flex items-center justify-center gap-2">
                                            @csrf @method('PATCH')
                                            <select name="status" class="border border-gray-300 rounded text-xs px-2 py-1 shadow-sm focus:outline-none">
                                                <option value="pending" {{ $ubinan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="harvested" {{ $ubinan->status == 'harvested' ? 'selected' : '' }}>Selesai</option>
                                                <option value="failed" {{ $ubinan->status == 'failed' ? 'selected' : '' }}>Gagal</option>
                                            </select>
                                            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-2 py-1 rounded text-xs font-bold transition">OK</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 font-medium">
                                        Belum ada data ubinan yang dicatat musim ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>

</body>
</html>