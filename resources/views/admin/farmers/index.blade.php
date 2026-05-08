<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Farmers - TaniCheck</title>
    
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

    <!-- STICKY NAVBAR -->
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
                <!-- Dashboard is now a regular link -->
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800/60 transition-colors">Dashboard</a>
                <a href="{{ route('admin.mapping') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800/60 transition-colors">Fields</a>
                <a href="{{ route('admin.ubinans.index') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800/60 transition-colors">Crops</a>
                <!-- Farmers is now the active link -->
                <a href="{{ route('admin.farmers.index') }}" class="px-4 py-2.5 rounded-lg text-sm font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 transition-colors">Farmers</a>
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
    <main class="flex-grow p-6 sm:p-10 max-w-7xl mx-auto w-full">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-5">
            <div>
                <h1 class="font-heading text-3xl md:text-4xl font-bold text-white tracking-tight">Farmer Management</h1>
                <p class="text-sm font-medium text-zinc-400 mt-2">View, edit, and manage all registered farmers in the system.</p>
            </div>
            <a href="{{ route('admin.farmers.register-farmer') }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-500 transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-900/30">
                <i class="fas fa-plus text-xs"></i> Register New Farmer
            </a>
        </div>

        <!-- Alert -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border-l-4 border-emerald-500 text-emerald-400 p-4 rounded-xl font-medium shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table Card -->
        <div class="bg-zinc-900/80 border border-zinc-800/60 rounded-2xl shadow-xl shadow-black/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-zinc-800/40">
                        <tr class="text-zinc-400 text-xs font-bold uppercase tracking-wider border-b border-zinc-800/60">
                            <th class="px-6 py-5">Name</th>
                            <th class="px-6 py-5">Phone Number</th>
                            <th class="px-6 py-5">Registered Lands</th>
                            <th class="px-6 py-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/60 text-sm">
                        @foreach($farmers as $farmer)
                        <tr class="hover:bg-zinc-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-zinc-200">{{ $farmer->name }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-zinc-400">
                                {{ $farmer->phone }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1.5 bg-sky-500/10 border border-sky-500/20 text-sky-400 rounded-full text-xs font-bold inline-block">
                                    {{ $farmer->lands->count() ?? 0 }} Fields
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-3">
                                <a href="{{ route('admin.farmers.edit', $farmer->id) }}" class="text-blue-400 hover:text-blue-300 font-semibold transition-colors">Edit</a>
                                
                                <form action="{{ route('admin.farmers.reset-pin', $farmer->id) }}" method="POST" class="inline" onsubmit="return confirm('Reset PIN to 123456?');">
                                    @csrf
                                    <button type="submit" class="text-amber-400 hover:text-amber-300 font-semibold transition-colors">Reset PIN</button>
                                </form>

                                <form action="{{ route('admin.farmers.destroy', $farmer->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this farmer? This might delete their land data too!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 font-semibold transition-colors">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="p-5 border-t border-zinc-800/60 bg-zinc-900/50">
                {{ $farmers->links() }}
            </div>
        </div>
        
    </main>

</body>
</html>