<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harvest Estimates - TaniCheck</title>
    
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Open Sans"', 'sans-serif'],
                        heading: ['Inter', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-[#09090b] font-sans antialiased text-zinc-200 min-h-screen flex flex-col">

    <nav class="sticky top-0 z-40 bg-zinc-900/80 backdrop-blur-md border-b border-zinc-800/60 transition-all">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-900/20">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 007.92 12.446A9 9 0 1112 2.992z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"></path>
                    </svg>
                </div>
                <span class="font-heading text-xl font-bold text-white tracking-tight">SiPanen</span>
            </div>
            
            <div class="hidden md:flex items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800/60 transition-colors">Dashboard</a>
                <a href="{{ route('admin.mapping') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800/60 transition-colors">Fields</a>
                <a href="{{ route('admin.ubinans.index') }}" class="px-4 py-2.5 rounded-lg text-sm font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 transition-colors">Crops</a>
                <a href="{{ route('admin.farmers.index') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800/60 transition-colors">Farmers</a>
            </div>

            <div class="flex items-center gap-3 sm:gap-4">
                <button class="relative p-2 rounded-lg text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800/60 transition-colors hidden sm:block">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"></path>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-emerald-500 border-2 border-zinc-900 rounded-full"></span>
                </button>
                <div class="w-9 h-9 rounded-full bg-zinc-800 flex items-center justify-center text-xs font-bold text-zinc-300 ring-2 ring-zinc-700/50 cursor-pointer hover:ring-zinc-600 transition-all">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A',0,2)) }}
                </div>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-6 sm:p-10 max-w-7xl mx-auto w-full">
        
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="font-heading text-3xl md:text-4xl font-bold text-white tracking-tight">Ubinan & Estimasi Panen</h1>
            <p class="text-sm font-medium text-zinc-400 mt-2">Calculate projected yields and track harvest statuses across all registered fields.</p>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-8 bg-emerald-500/10 border-l-4 border-emerald-500 text-emerald-400 p-4 rounded-xl shadow-sm font-medium flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 bg-red-500/10 border-l-4 border-red-500 text-red-400 p-4 rounded-xl shadow-sm">
                <p class="font-bold mb-1"><i class="fas fa-exclamation-triangle"></i> Ada Kesalahan:</p>
                <ul class="list-disc ml-6 text-sm font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- LEFT COLUMN: INPUT FORM -->
            <div class="w-full lg:w-1/3">
                <div class="bg-zinc-900/80 rounded-2xl shadow-xl shadow-black/20 border border-zinc-800/60 p-6 sm:p-8 h-fit sticky top-24">
                    <h2 class="text-lg font-bold text-white mb-1 flex items-center gap-2">
                        <i class="fas fa-calculator text-emerald-400"></i> Input Data Ubinan
                    </h2>
                    <p class="text-xs text-zinc-500 mb-6 leading-relaxed">Sistem akan otomatis menghitung estimasi total panen berdasarkan sampel 2.5m x 2.5m.</p>

                    <form action="{{ route('admin.ubinans.store') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Pilih Lahan</label>
                            <select name="land_id" required class="w-full bg-zinc-900 border border-zinc-700 text-zinc-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all shadow-sm">
                                <option value="" disabled selected>-- Pilih Lahan --</option>
                                @foreach($lands as $land)
                                    <option value="{{ $land->id }}">{{ $land->nickname }} ({{ $land->user->name }} - {{ $land->area_size }} Ha)</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Berat Sampel (Kg)</label>
                            <input type="number" step="0.01" name="sample_weight_kg" placeholder="Contoh: 5.45" required class="w-full bg-zinc-900 border border-zinc-700 text-white placeholder-zinc-600 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all shadow-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Rencana Tanggal Panen</label>
                            <input type="date" name="projected_harvest_date" required class="w-full bg-zinc-900 border border-zinc-700 text-white rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all shadow-sm [color-scheme:dark]">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Catatan Tambahan</label>
                            <textarea name="notes" rows="3" class="w-full bg-zinc-900 border border-zinc-700 text-white placeholder-zinc-600 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all shadow-sm resize-none" placeholder="Kondisi cuaca, hama, dll..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-emerald-900/30 transition-all flex items-center justify-center gap-2 mt-2">
                            Hitung Estimasi <i class="fas fa-arrow-right text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- RIGHT COLUMN: ESTIMATE LIST -->
            <div class="w-full lg:w-2/3">
                <div class="bg-zinc-900/80 rounded-2xl shadow-xl shadow-black/20 border border-zinc-800/60 overflow-hidden">
                    <div class="px-6 py-5 border-b border-zinc-800/60 bg-zinc-900/50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white">Daftar Estimasi</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead class="bg-zinc-800/40">
                                <tr class="text-zinc-400 text-xs uppercase tracking-wider border-b border-zinc-800/60">
                                    <th class="px-6 py-4 font-bold">Lahan & Petani</th>
                                    <th class="px-6 py-4 font-bold text-right">Sampel (Kg)</th>
                                    <th class="px-6 py-4 font-bold text-right">Estimasi (Kg)</th>
                                    <th class="px-6 py-4 font-bold text-center">Status</th>
                                    <th class="px-6 py-4 font-bold text-center">Update</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-zinc-800/60">
                                @forelse($ubinans as $ubinan)
                                <tr class="hover:bg-zinc-800/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-zinc-200">{{ $ubinan->land->nickname }}</p>
                                        <p class="text-xs text-zinc-500 mt-1">{{ $ubinan->land->user->name }} &bull; Tgl: {{ \Carbon\Carbon::parse($ubinan->projected_harvest_date)->format('d M Y') }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-zinc-400">
                                        {{ number_format($ubinan->sample_weight_kg, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-emerald-400 text-base">
                                        {{ number_format($ubinan->estimated_yield_kg, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($ubinan->status === 'pending')
                                            <span class="px-3 py-1.5 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-full text-xs font-bold inline-block">Pending</span>
                                        @elseif($ubinan->status === 'harvested')
                                            <span class="px-3 py-1.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-full text-xs font-bold inline-block">Selesai</span>
                                        @else
                                            <span class="px-3 py-1.5 bg-red-500/10 border border-red-500/20 text-red-400 rounded-full text-xs font-bold inline-block">Gagal</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.ubinans.update-status', $ubinan->id) }}" method="POST" class="flex items-center justify-center gap-2">
                                            @csrf @method('PATCH')
                                            <select name="status" class="bg-zinc-900 border border-zinc-700 text-zinc-300 rounded-lg text-xs px-2.5 py-1.5 shadow-sm focus:ring-2 focus:ring-emerald-500/50 outline-none transition-all">
                                                <option value="pending" {{ $ubinan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="harvested" {{ $ubinan->status == 'harvested' ? 'selected' : '' }}>Selesai</option>
                                                <option value="failed" {{ $ubinan->status == 'failed' ? 'selected' : '' }}>Gagal</option>
                                            </select>
                                            <button type="submit" class="bg-zinc-700 hover:bg-zinc-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-colors shadow-sm">OK</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <i class="fas fa-seedling text-3xl text-zinc-700 mb-3 block"></i>
                                        <p class="text-zinc-500 font-medium">Belum ada data ubinan yang dicatat musim ini.</p>
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