<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiPanen - Admin Dashboard</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Make sure FontAwesome is loaded for the dynamic weather icons -->
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

<body class="bg-[#09090b] text-zinc-200 font-sans antialiased min-h-screen flex flex-col">

    <!-- STICKY NAVBAR -->
    <nav class="sticky top-0 z-40 bg-zinc-900/80 backdrop-blur-md border-b border-zinc-800/60 transition-all">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">

            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-900/20">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 007.92 12.446A9 9 0 1112 2.992z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"></path>
                    </svg>
                </div>
                <span class="font-heading text-xl font-bold text-white tracking-tight">SiPanen</span>
            </div>

            <!-- Desktop Links -->
            <div class="hidden md:flex items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-lg text-sm font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 transition-colors">Dashboard</a>
                <a href="{{ route('admin.mapping') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800/60 transition-colors">Fields</a>
                <a href="{{ route('admin.ubinans.index') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800/60 transition-colors">Crops</a>
                <a href="{{ route('admin.farmers.index') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800/60 transition-colors">Farmers</a>
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Notification Bell -->
                <button class="relative p-2 rounded-lg text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800/60 transition-colors hidden sm:block">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"></path>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-emerald-500 border-2 border-zinc-900 rounded-full"></span>
                </button>

                <!-- PROFILE DROPDOWN CONTAINER -->
                <div class="relative">
                    <!-- Profile Avatar Button -->
                    <button id="profileBtn" class="w-9 h-9 rounded-full bg-zinc-800 flex items-center justify-center text-xs font-bold text-zinc-300 ring-2 ring-zinc-700/50 cursor-pointer hover:ring-zinc-500 transition-all focus:outline-none">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                    </button>

                    <!-- The Popup Menu -->
                    <div id="profileDropdown" class="hidden absolute right-0 mt-3 w-56 bg-zinc-900 border border-zinc-800/80 rounded-xl shadow-2xl py-2 z-50 origin-top-right transition-all">

                        <!-- THE SECURE LOGOUT FORM -->
                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm font-semibold text-rose-500 hover:bg-rose-500/10 hover:text-rose-400 transition-colors flex items-center gap-2 group">
                                <!-- Logout Icon -->
                                <svg class="w-4 h-4 text-rose-500/70 group-hover:text-rose-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                </svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="relative flex-grow">
        <section class="py-6 sm:py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-screen-2xl mx-auto">

                <!-- HEADER AREA -->
                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-8 sm:mb-10">
                    <div>
                        <!-- DYNAMIC DATE INJECTED HERE -->
                        <p id="dynamic-date" class="text-sm font-medium text-zinc-500 mb-1.5">Loading date...</p>
                        <h1 class="font-heading text-3xl md:text-4xl font-bold text-white tracking-tight">
                            Welcome back, <span class="text-emerald-400">{{ auth()->user()->name ?? 'Admin' }}</span>
                        </h1>
                        <p class="text-zinc-400 text-base mt-2">Here's what's happening across your <strong class="text-zinc-200">{{ $stats['active_fields'] ?? 0 }}</strong> active fields.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="openPriceModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-sm font-semibold bg-zinc-800 border border-zinc-700/60 text-zinc-200 hover:bg-zinc-700 hover:text-white transition-all flex items-center justify-center gap-2 shadow-sm shadow-black/20">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-3h6m-6 0a3 3 0 103 3m3-3a3 3 0 10-3-3"></path>
                            </svg>
                            <span>Manage Prices</span>
                        </button>
                    </div>
                </div>

                <!-- STATS ROW -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8 sm:mb-10">

                    <!-- Active Fields -->
                    <div class="bg-zinc-900/80 border border-zinc-800/60 rounded-2xl p-6 hover:-translate-y-1 hover:border-zinc-700 hover:shadow-xl hover:shadow-black/20 transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center group-hover:bg-emerald-500/20 transition-colors">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 4.5H21"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Registered</span>
                        </div>
                        <p class="font-heading text-4xl font-extrabold text-white">{{ $stats['active_fields'] }}</p>
                        <p class="text-sm font-medium text-zinc-500 mt-1">Active Fields</p>
                    </div>

                    <!-- Total Area -->
                    <div class="bg-zinc-900/80 border border-zinc-800/60 rounded-2xl p-6 hover:-translate-y-1 hover:border-zinc-700 hover:shadow-xl hover:shadow-black/20 transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center group-hover:bg-amber-500/20 transition-colors">
                                <svg class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">System Total</span>
                        </div>
                        <p class="font-heading text-4xl font-extrabold text-white">
                            {{ number_format($stats['total_area'], 2) }} <span class="text-xl text-zinc-500 font-medium">meter squared</span>
                        </p>
                        <p class="text-sm font-medium text-zinc-500 mt-1">Total Land Area</p>
                    </div>

                    <!-- Est Harvest -->
                    <div class="bg-zinc-900/80 border border-zinc-800/60 rounded-2xl p-6 hover:-translate-y-1 hover:border-zinc-700 hover:shadow-xl hover:shadow-black/20 transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-12 h-12 rounded-xl bg-sky-500/10 flex items-center justify-center group-hover:bg-sky-500/20 transition-colors">
                                <svg class="w-6 h-6 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-sky-500/10 text-sky-400 border border-sky-500/20">Season Est.</span>
                        </div>
                        <p class="font-heading text-4xl font-extrabold text-white">
                            {{ number_format($stats['est_harvest_tons'], 1) }}<span class="text-xl text-zinc-500 font-medium">t</span>
                        </p>
                        <p class="text-sm font-medium text-zinc-500 mt-1">Total Yield Output</p>
                    </div>

                    <!-- Total Farmers / Alerts -->
                    <div class="bg-zinc-900/80 border {{ $stats['pending_alerts'] > 0 ? 'border-rose-500/50 shadow-lg shadow-rose-900/20' : 'border-zinc-800/60' }} rounded-2xl p-6 hover:-translate-y-1 hover:border-rose-500/80 transition-all duration-300 group">
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-12 h-12 rounded-xl {{ $stats['pending_alerts'] > 0 ? 'bg-rose-500/20 text-rose-400' : 'bg-zinc-800 text-zinc-400' }} flex items-center justify-center transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-zinc-800 text-zinc-300 border border-zinc-700">Farmers: {{ $stats['total_farmers'] }}</span>
                        </div>
                        <p class="font-heading text-4xl font-extrabold {{ $stats['pending_alerts'] > 0 ? 'text-rose-400' : 'text-white' }}">{{ $stats['pending_alerts'] }}</p>
                        <p class="text-sm font-medium text-zinc-500 mt-1">Pending Validations</p>
                    </div>
                </div>

                <!-- MAIN CONTENT GRID -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8">

                    <!-- Left Column: Chart + Crop Table -->
                    <div class="lg:col-span-8 flex flex-col gap-6 sm:gap-8">

                        <!-- Yield Chart Placeholder -->
                        <div class="bg-zinc-900/80 border border-zinc-800/60 rounded-2xl p-6 sm:p-8">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                                <div>
                                    <h2 class="font-heading text-xl font-bold text-white">Yield Performance</h2>
                                    <p class="text-sm text-zinc-500 mt-1">Monthly harvest output across all fields</p>
                                </div>
                                <div class="flex items-center gap-1.5 bg-zinc-800/80 rounded-xl p-1.5 border border-zinc-700/50">
                                    <button class="px-4 py-2 rounded-lg text-xs font-bold bg-zinc-700 text-white shadow-sm transition-colors">6M</button>
                                    <button class="px-4 py-2 rounded-lg text-xs font-bold text-zinc-400 hover:text-zinc-200 hover:bg-zinc-700/50 transition-colors">1Y</button>
                                    <button class="px-4 py-2 rounded-lg text-xs font-bold text-zinc-400 hover:text-zinc-200 hover:bg-zinc-700/50 transition-colors">All</button>
                                </div>
                            </div>
                            <div class="chart-area relative h-64 flex items-end gap-3 sm:gap-4 px-2">
                                <div class="flex-1 flex flex-col items-center gap-2">
                                    <div class="w-full bg-emerald-500/20 rounded-t-xl relative overflow-hidden transition-all duration-500 hover:bg-emerald-500/30" style="height: 45%;">
                                        <div class="absolute inset-0 bg-gradient-to-t from-emerald-500/40 to-emerald-500/5"></div>
                                    </div>
                                    <span class="text-xs font-medium text-zinc-500">Jul</span>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-2">
                                    <div class="w-full bg-emerald-500/20 rounded-t-xl relative overflow-hidden transition-all duration-500 hover:bg-emerald-500/30" style="height: 62%;">
                                        <div class="absolute inset-0 bg-gradient-to-t from-emerald-500/40 to-emerald-500/5"></div>
                                    </div>
                                    <span class="text-xs font-medium text-zinc-500">Aug</span>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-2">
                                    <div class="w-full bg-emerald-500/20 rounded-t-xl relative overflow-hidden transition-all duration-500 hover:bg-emerald-500/30" style="height: 78%;">
                                        <div class="absolute inset-0 bg-gradient-to-t from-emerald-500/40 to-emerald-500/5"></div>
                                    </div>
                                    <span class="text-xs font-medium text-zinc-500">Sep</span>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-2">
                                    <div class="w-full bg-emerald-500/20 rounded-t-xl relative overflow-hidden transition-all duration-500 hover:bg-emerald-500/30" style="height: 91%;">
                                        <div class="absolute inset-0 bg-gradient-to-t from-emerald-500/40 to-emerald-500/5"></div>
                                    </div>
                                    <span class="text-xs font-medium text-zinc-500">Oct</span>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-2">
                                    <div class="w-full bg-emerald-500/20 rounded-t-xl relative overflow-hidden transition-all duration-500 hover:bg-emerald-500/30" style="height: 70%;">
                                        <div class="absolute inset-0 bg-gradient-to-t from-emerald-500/40 to-emerald-500/5"></div>
                                    </div>
                                    <span class="text-xs font-medium text-zinc-500">Nov</span>
                                </div>
                                <div class="flex-1 flex flex-col items-center gap-2">
                                    <div class="w-full rounded-t-xl relative overflow-hidden bg-emerald-500/30 ring-1 ring-emerald-500/40 shadow-[0_0_15px_rgba(16,185,129,0.2)] transition-all duration-500" style="height: 85%;">
                                        <div class="absolute inset-0 bg-gradient-to-t from-emerald-500/60 to-emerald-500/10"></div>
                                    </div>
                                    <span class="text-xs font-bold text-emerald-400">Dec</span>
                                </div>
                            </div>
                        </div>

                        <!-- Crop Table -->
                        <div class="bg-zinc-900/80 border border-zinc-800/60 rounded-2xl p-6 sm:p-8">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                                <h2 class="font-heading text-xl font-bold text-white">Active Crops</h2>
                                <a href="{{ route('admin.ubinans.index') }}" class="text-sm text-emerald-400 hover:text-emerald-300 font-semibold flex items-center gap-1 transition-colors">
                                    View all crops <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                            <div class="overflow-x-auto rounded-xl border border-zinc-800/50">
                                <table class="w-full text-sm whitespace-nowrap">
                                    <thead class="bg-zinc-800/40">
                                        <tr>
                                            <th class="px-5 py-4 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wider">Crop</th>
                                            <th class="px-5 py-4 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wider">Field</th>
                                            <th class="px-5 py-4 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wider">Status</th>
                                            <th class="px-5 py-4 text-left text-xs font-semibold text-zinc-400 uppercase tracking-wider">Health</th>
                                            <th class="px-5 py-4 text-right text-xs font-semibold text-zinc-400 uppercase tracking-wider">Est. Harvest</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-800/60">
                                        @forelse($recent_mappings ?? [] as $mapping)
                                        <tr class="hover:bg-zinc-800/30 transition-colors">
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-400 text-sm font-bold border border-amber-500/20">
                                                        {{ substr($mapping['crop'], 0, 1) }}
                                                    </div>
                                                    <span class="font-semibold text-zinc-200">{{ $mapping['crop'] }}</span>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 text-zinc-400">{{ $mapping['location'] }}</td>
                                            <td class="px-5 py-4">
                                                <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Growing</span>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-20 h-2 rounded-full bg-zinc-800 overflow-hidden shadow-inner">
                                                        <div class="h-full w-4/5 bg-emerald-500 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                                                    </div>
                                                    <span class="text-xs font-medium text-zinc-400">80%</span>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 text-right font-medium text-zinc-300">Mar 2026</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="px-5 py-8 text-center text-zinc-500 text-sm italic bg-zinc-900/30">No active crops found. Time to plant some seeds!</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Weather + Activity + Tasks -->
                    <div class="lg:col-span-4 flex flex-col gap-6 sm:gap-8">

                        <!-- Dynamic Weather Widget via Open-Meteo -->
                        <div id="weather-widget" class="bg-gradient-to-br from-zinc-900 to-zinc-900/50 border border-zinc-800/60 rounded-2xl p-6 relative overflow-hidden transition-all">
                            <!-- Decorative background element -->
                            <div class="absolute -right-10 -top-10 w-40 h-40 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

                            <h3 class="font-heading text-sm font-bold text-zinc-400 uppercase tracking-wider mb-5 flex items-center gap-2">
                                <i class="fas fa-satellite-dish text-xs text-sky-400"></i> Local Forecast
                            </h3>

                            <div class="flex items-center gap-5 mb-6">
                                <div class="w-16 h-16 rounded-2xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center shadow-lg shadow-sky-900/20">
                                    <i id="weather-icon" class="fas fa-spinner fa-spin text-2xl text-sky-400"></i>
                                </div>
                                <div>
                                    <p id="weather-temp" class="font-heading text-4xl font-extrabold text-white tracking-tighter">--°<span class="text-2xl text-zinc-500 font-medium">C</span></p>
                                    <p id="weather-desc" class="text-sm font-medium text-sky-400 mt-1">Fetching Data...</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div class="text-center p-3.5 rounded-xl bg-zinc-800/50 border border-zinc-700/30 hover:bg-zinc-800 transition-colors">
                                    <p class="text-xs font-semibold text-zinc-500 mb-1.5">Humidity</p>
                                    <p id="weather-humidity" class="text-sm font-bold text-zinc-200">--%</p>
                                </div>
                                <div class="text-center p-3.5 rounded-xl bg-zinc-800/50 border border-zinc-700/30 hover:bg-zinc-800 transition-colors">
                                    <p class="text-xs font-semibold text-zinc-500 mb-1.5">Wind</p>
                                    <p class="text-sm font-bold text-zinc-200"><span id="weather-wind">--</span> <span class="text-xs font-medium text-zinc-500">km/h</span></p>
                                </div>
                                <div class="text-center p-3.5 rounded-xl bg-zinc-800/50 border border-zinc-700/30 hover:bg-zinc-800 transition-colors">
                                    <p class="text-xs font-semibold text-zinc-500 mb-1.5">Rain</p>
                                    <p class="text-sm font-bold text-zinc-200"><span id="weather-rain">--</span> <span class="text-xs font-medium text-zinc-500">mm</span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming Tasks -->
                        <div class="bg-zinc-900/80 border border-zinc-800/60 rounded-2xl p-6">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="font-heading text-sm font-bold text-zinc-400 uppercase tracking-wider">Upcoming Tasks</h3>
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-zinc-800 text-zinc-300 border border-zinc-700">7 total</span>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-start gap-4 p-3.5 rounded-xl bg-zinc-800/40 border border-zinc-700/50 hover:border-zinc-600 hover:bg-zinc-800/80 transition-all cursor-pointer">
                                    <div class="w-2.5 h-2.5 rounded-full bg-rose-400 mt-1.5 shrink-0 shadow-[0_0_8px_rgba(251,113,133,0.6)]"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-zinc-200 truncate">Inspect barley field — pest alert</p>
                                        <p class="text-xs font-medium text-zinc-500 mt-1">Today, 9:00 AM</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4 p-3.5 rounded-xl bg-zinc-800/40 border border-zinc-700/50 hover:border-zinc-600 hover:bg-zinc-800/80 transition-all cursor-pointer">
                                    <div class="w-2.5 h-2.5 rounded-full bg-amber-400 mt-1.5 shrink-0 shadow-[0_0_8px_rgba(251,191,36,0.6)]"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-zinc-200 truncate">Fertilizer application — North Ridge</p>
                                        <p class="text-xs font-medium text-zinc-500 mt-1">Tomorrow, 7:00 AM</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4 p-3.5 rounded-xl bg-zinc-800/40 border border-zinc-700/50 hover:border-zinc-600 hover:bg-zinc-800/80 transition-all cursor-pointer">
                                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 mt-1.5 shrink-0 shadow-[0_0_8px_rgba(52,211,153,0.6)]"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-zinc-200 truncate">Soybean harvest — South Flat</p>
                                        <p class="text-xs font-medium text-zinc-500 mt-1">Jan 18, 6:00 AM</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="bg-zinc-900/80 border border-zinc-800/60 rounded-2xl p-6">
                            <h3 class="font-heading text-sm font-bold text-zinc-400 uppercase tracking-wider mb-6">Recent Activity</h3>
                            <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-zinc-800 before:to-transparent">
                                <div class="relative flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-full bg-zinc-900 border-2 border-emerald-500/30 flex items-center justify-center shrink-0 z-10 shadow-sm shadow-black/40">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                                        </svg>
                                    </div>
                                    <div class="pt-1">
                                        <p class="text-sm text-zinc-400 leading-relaxed">
                                            Corn planting completed on <span class="text-zinc-200 font-semibold">Valley East</span>
                                        </p>
                                        <p class="text-xs font-medium text-zinc-600 mt-1">2 hours ago</p>
                                    </div>
                                </div>
                                <div class="relative flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-full bg-zinc-900 border-2 border-sky-500/30 flex items-center justify-center shrink-0 z-10 shadow-sm shadow-black/40">
                                        <svg class="w-4 h-4 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                                        </svg>
                                    </div>
                                    <div class="pt-1">
                                        <p class="text-sm text-zinc-400 leading-relaxed">
                                            Soil report uploaded for <span class="text-zinc-200 font-semibold">West Bank</span>
                                        </p>
                                        <p class="text-xs font-medium text-zinc-600 mt-1">5 hours ago</p>
                                    </div>
                                </div>
                                <div class="relative flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-full bg-zinc-900 border-2 border-amber-500/30 flex items-center justify-center shrink-0 z-10 shadow-sm shadow-black/40">
                                        <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="pt-1">
                                        <p class="text-sm text-zinc-400 leading-relaxed">
                                            Scheduled irrigation for <span class="text-zinc-200 font-semibold">North Ridge</span>
                                        </p>
                                        <p class="text-xs font-medium text-zinc-600 mt-1">Yesterday</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- PRICE MODAL -->
    <div id="priceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-black/60 backdrop-blur-sm" aria-hidden="true" onclick="closePriceModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-zinc-900 rounded-2xl shadow-2xl shadow-black/50 sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full border border-zinc-700/60 relative z-10">

                <div class="px-6 sm:px-8 pt-6 pb-5 border-b border-zinc-800/60 flex justify-between items-center bg-zinc-900/50">
                    <h3 class="text-xl font-heading font-extrabold text-white tracking-tight" id="modal-title">Manajemen Harga Pasar</h3>
                    <button onclick="closePriceModal()" class="text-zinc-500 hover:text-red-400 bg-zinc-800/50 hover:bg-zinc-800 p-2 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mb-8 bg-zinc-800/40 p-5 sm:p-6 rounded-xl border border-zinc-700/50 shadow-inner">
                    <h4 class="text-sm font-bold text-emerald-400 mb-4 uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-edit text-xs"></i> Update Harga Baru
                    </h4>
                    <form action="{{ route('admin.prices.store') }}" method="POST" class="flex flex-col sm:flex-row gap-5 items-end">
                        @csrf

                        <div class="flex-1 w-full relative" id="crop-selector-group">
                            <label class="flex justify-between items-end text-xs font-bold text-zinc-400 mb-2">
                                <span id="crop-label" class="transition-colors">Komoditas</span>
                                <button type="button" id="cancel-new-crop" onclick="toggleNewCrop(false)" class="hidden text-rose-400 hover:text-rose-300 font-bold transition-colors">
                                    <i class="fas fa-times mr-1"></i>Batal
                                </button>
                            </label>

                            <select id="crop_select" name="crop_name" onchange="checkNewCrop(this)" required class="w-full px-4 py-2.5 border border-zinc-700 rounded-lg focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 text-sm bg-zinc-900 text-zinc-200 outline-none transition-all shadow-sm">
                                <option value="" disabled selected>Pilih Komoditas...</option>

                                @foreach($existingCrops ?? [] as $crop)
                                <option value="{{ $crop }}">{{ $crop }}</option>
                                @endforeach

                                <option disabled>──────────</option>

                                <option value="NEW" class="text-emerald-400 font-bold">+ Tambah Komoditas Baru...</option>
                            </select>

                            <input type="text" id="crop_input" name="new_crop_name" placeholder="Cth: Cabai Merah" disabled class="hidden w-full px-4 py-2.5 border border-emerald-500/50 rounded-lg focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 text-sm bg-zinc-900 text-white placeholder-zinc-600 outline-none transition-all shadow-[0_0_10px_rgba(16,185,129,0.1)]">
                        </div>

                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-zinc-400 mb-2">Harga Baru (Rp/kg)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-sm font-medium text-zinc-500">Rp</span>
                                <input type="number" name="price_per_kg" required min="0" step="100" placeholder="12500" class="w-full pl-10 pr-4 py-2.5 border border-zinc-700 rounded-lg focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 text-sm bg-zinc-900 text-zinc-200 placeholder-zinc-600 outline-none transition-all shadow-sm">
                            </div>
                        </div>

                        <div class="w-full sm:w-auto mt-4 sm:mt-0">
                            <button type="submit" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-2.5 px-6 rounded-lg shadow-lg shadow-emerald-900/30 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto rounded-xl border border-zinc-700/60 shadow-sm">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead class="bg-zinc-800/80">
                            <tr>
                                <th class="px-5 py-4 text-left text-xs font-bold text-zinc-400 uppercase tracking-wider">Komoditas</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-zinc-400 uppercase tracking-wider">Harga Aktif</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-zinc-400 uppercase tracking-wider">Tgl Update</th>
                                <th class="px-5 py-4 text-left text-xs font-bold text-zinc-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <table class="w-full text-sm whitespace-nowrap">
                            <thead class="bg-zinc-800/80">
                                <tr>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-zinc-400 uppercase tracking-wider">Komoditas</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-zinc-400 uppercase tracking-wider">Harga Aktif</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-zinc-400 uppercase tracking-wider">Tgl Update</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-zinc-400 uppercase tracking-wider">Status</th>
                                    <th class="px-5 py-4 text-center text-xs font-bold text-zinc-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-800/60">
                                @forelse($latest_prices ?? [] as $price)
                                <tr class="hover:bg-zinc-800/40 transition-colors group">
                                    <td class="px-5 py-4 font-bold text-zinc-200">{{ $price->crop_name }}</td>
                                    <td class="px-5 py-4 font-bold text-emerald-400 text-base">Rp {{ number_format($price->price_per_kg, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 text-zinc-400 font-medium text-sm">{{ \Carbon\Carbon::parse($price->effective_date)->format('d M Y, H:i') }}</td>
                                    <td class="px-5 py-4"><span class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold px-3 py-1.5 rounded-full">Aktif</span></td>

                                    <td class="px-5 py-4 text-center">
                                        <form action="{{ route('admin.prices.destroy', $price->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin mau hapus harga komoditas ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:bg-rose-500/20 hover:text-rose-300 p-2 rounded-lg transition-colors opacity-70 group-hover:opacity-100" title="Hapus Harga">
                                                <i class="fas fa-trash-alt text-sm"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center text-zinc-500 text-sm italic bg-zinc-900/30">
                                        <i class="fas fa-box-open text-3xl mb-3 text-zinc-700 block"></i>
                                        Belum ada data harga pasar. Silakan tambahkan di form atas.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </table>
                </div>
            </div>

            <div class="px-6 sm:px-8 py-5 bg-zinc-900/80 border-t border-zinc-800/60 text-right rounded-b-2xl">
                <button onclick="closePriceModal()" class="bg-zinc-800 hover:bg-zinc-700 text-zinc-300 font-bold py-2.5 px-6 border border-zinc-700 rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-zinc-600 outline-none">
                    Tutup Panel
                </button>
            </div>
        </div>
    </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        // Modal Logic
        function openPriceModal() {
            document.getElementById('priceModal').classList.remove('hidden');
        }

        function closePriceModal() {
            document.getElementById('priceModal').classList.add('hidden');
        }
        window.onclick = function(event) {
            if (event.target.classList.contains('bg-black/60')) {
                closePriceModal();
            }
        }

        // Live Date Logic
        document.addEventListener('DOMContentLoaded', () => {
            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            document.getElementById('dynamic-date').textContent = new Date().toLocaleDateString('en-US', dateOptions);

            // Initiate Weather Fetch
            getWeather();
        });

        // Weather Logic via Open-Meteo
        const weatherCodes = {
            0: {
                label: 'Clear sky',
                icon: 'fa-sun'
            },
            1: {
                label: 'Mainly clear',
                icon: 'fa-cloud-sun'
            },
            2: {
                label: 'Partly cloudy',
                icon: 'fa-cloud-sun'
            },
            3: {
                label: 'Overcast',
                icon: 'fa-cloud'
            },
            45: {
                label: 'Fog',
                icon: 'fa-smog'
            },
            48: {
                label: 'Depositing rime fog',
                icon: 'fa-smog'
            },
            51: {
                label: 'Light drizzle',
                icon: 'fa-cloud-rain'
            },
            53: {
                label: 'Moderate drizzle',
                icon: 'fa-cloud-rain'
            },
            55: {
                label: 'Dense drizzle',
                icon: 'fa-cloud-rain'
            },
            61: {
                label: 'Slight rain',
                icon: 'fa-cloud-rain'
            },
            63: {
                label: 'Moderate rain',
                icon: 'fa-cloud-showers-heavy'
            },
            65: {
                label: 'Heavy rain',
                icon: 'fa-cloud-showers-heavy'
            },
            80: {
                label: 'Slight showers',
                icon: 'fa-cloud-showers-heavy'
            },
            81: {
                label: 'Moderate showers',
                icon: 'fa-cloud-showers-heavy'
            },
            82: {
                label: 'Violent showers',
                icon: 'fa-cloud-showers-heavy'
            },
            95: {
                label: 'Thunderstorm',
                icon: 'fa-bolt'
            },
            96: {
                label: 'Thunderstorm w/ hail',
                icon: 'fa-bolt'
            },
            99: {
                label: 'Heavy thunderstorm',
                icon: 'fa-bolt'
            },
        };

        function fetchWeatherData(lat, lng) {
            const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current=temperature_2m,relative_humidity_2m,precipitation,weather_code,wind_speed_10m&timezone=auto`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    const current = data.current;
                    const codeInfo = weatherCodes[current.weather_code] || {
                        label: 'Unknown',
                        icon: 'fa-cloud'
                    };

                    document.getElementById('weather-temp').innerHTML = `${Math.round(current.temperature_2m)}°<span class="text-2xl text-zinc-500 font-medium">C</span>`;
                    document.getElementById('weather-desc').textContent = codeInfo.label;
                    document.getElementById('weather-humidity').textContent = `${current.relative_humidity_2m}%`;
                    document.getElementById('weather-wind').textContent = current.wind_speed_10m;
                    document.getElementById('weather-rain').textContent = current.precipitation;

                    // Update Icon
                    const iconEl = document.getElementById('weather-icon');
                    iconEl.className = `fas ${codeInfo.icon} text-3xl text-sky-400`;
                })
                .catch(err => {
                    console.error("Weather fetch error:", err);
                    document.getElementById('weather-desc').textContent = "Data unavailable";
                    document.getElementById('weather-icon').className = "fas fa-exclamation-triangle text-2xl text-rose-400";
                });
        }

        function getWeather() {
            // Default fallback is the center point used in your Map Tracker [-7.5, 110.0] (Central Java area)
            const fallbackLat = -7.5;
            const fallbackLng = 110.0;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    // Success
                    (pos) => fetchWeatherData(pos.coords.latitude, pos.coords.longitude),
                    // Error (e.g. user denied permission)
                    (err) => {
                        console.log("Geo location denied or failed. Using fallback coordinates.");
                        fetchWeatherData(fallbackLat, fallbackLng);
                    }, {
                        timeout: 5000
                    }
                );
            } else {
                fetchWeatherData(fallbackLat, fallbackLng);
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileBtn = document.getElementById('profileBtn');
            const dropdown = document.getElementById('profileDropdown');

            // Toggle dropdown when clicking the profile picture
            profileBtn.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevents click from instantly closing it
                dropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking anywhere else on the screen
            document.addEventListener('click', function(event) {
                if (!dropdown.contains(event.target) && !profileBtn.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    </script>

    <script>
        // Add this inside your <script> tags at the bottom
        function checkNewCrop(selectElement) {
            if (selectElement.value === 'NEW') {
                toggleNewCrop(true);
            }
        }

        function toggleNewCrop(isNew) {
            const selectEl = document.getElementById('crop_select');
            const inputEl = document.getElementById('crop_input');
            const cancelBtn = document.getElementById('cancel-new-crop');
            const label = document.getElementById('crop-label');

            if (isNew) {
                // Hide select, show text input
                selectEl.classList.add('hidden');
                selectEl.removeAttribute('required');

                inputEl.classList.remove('hidden');
                inputEl.removeAttribute('disabled');
                inputEl.setAttribute('required', 'true');
                inputEl.focus();

                // Update label styling
                cancelBtn.classList.remove('hidden');
                label.innerText = 'Nama Komoditas Baru';
                label.classList.add('text-emerald-400');
            } else {
                // Hide text input, show select
                inputEl.classList.add('hidden');
                inputEl.setAttribute('disabled', 'true');
                inputEl.removeAttribute('required');
                inputEl.value = ''; // clear whatever they typed

                selectEl.classList.remove('hidden');
                selectEl.setAttribute('required', 'true');
                selectEl.value = ''; // reset back to default option

                // Reset label styling
                cancelBtn.classList.add('hidden');
                label.innerText = 'Komoditas';
                label.classList.remove('text-emerald-400');
            }
        }
    </script>

</body>

</html>