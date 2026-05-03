<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaniCheck - Admin Dashboard</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Open Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>

<body
    class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500 overflow-x-hidden">

    <div class="absolute w-full bg-green-600 h-72 z-0"></div>

    <!-- SIDEBAR (tidak berubah) -->
    <aside
        class="fixed inset-y-0 left-0 flex-wrap items-center justify-between w-full p-0 my-4 ml-4 overflow-y-auto antialiased transition-transform duration-200 bg-white border-0 shadow-xl max-w-64 z-50 rounded-2xl hidden md:block">
        <div class="h-[76px] border-b border-gray-100 w-full flex items-center px-8 py-6">
            <h2 class="text-2xl font-bold text-green-600 m-0">TaniCheck</h2>
            <p class="text-xs text-gray-500 ml-2 mt-2">Admin</p>
        </div>
        <div class="items-center block w-auto max-h-screen overflow-auto grow basis-full w-full mt-4">
            <ul class="flex flex-col pl-0 mb-0 w-full">
                <li class="mt-0.5 w-full">
                    <a href="{{ route('admin.dashboard') }}"
                        class="py-[11px] bg-green-500/10 text-sm my-0 mx-4 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors">
                        <div
                            class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="relative top-0 text-sm leading-normal text-green-600 fas fa-tv"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-in-out">Dashboard</span>
                    </a>
                </li>
                <li class="mt-0.5 w-full">
                    <a href="{{ route('admin.farmers.index') }}"
                        class="py-[11px] text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors hover:bg-gray-50 rounded-lg">
                        <div
                            class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="relative top-0 text-sm leading-normal text-blue-500 fas fa-users"></i>
                        </div>
                        <span
                            class="ml-1 text-slate-700 font-medium duration-300 opacity-100 pointer-events-none ease-in-out">Farmers</span>
                    </a>
                </li>
                <li class="mt-0.5 w-full">
                    <a href="{{ route('admin.mapping') }}"
                        class="py-[11px] text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors hover:bg-gray-50 rounded-lg">
                        <div
                            class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="relative top-0 text-sm leading-normal text-orange-500 fas fa-map-marked-alt"></i>
                        </div>
                        <span
                            class="ml-1 text-slate-700 font-medium duration-300 opacity-100 pointer-events-none ease-in-out">Map
                            Tracker</span>
                    </a>
                </li>
                <li class="mt-0.5 w-full">
                    <a href="{{ route('admin.ubinans.index') }}"
                        class="py-[11px] text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors hover:bg-gray-50 rounded-lg">
                        <div
                            class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="relative top-0 text-sm leading-normal text-yellow-500 fas fa-seedling"></i>
                        </div>
                        <span
                            class="ml-1 text-slate-700 font-medium duration-300 opacity-100 pointer-events-none ease-in-out">Harvest
                            Estimates</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out md:ml-[17rem] rounded-xl z-10">

        <!-- TOP NAVBAR -->
        <nav
            class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 mt-4 transition-all shadow-none duration-250 rounded-2xl lg:flex-nowrap lg:justify-start">
            <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
                <nav>
                    <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
                        <li class="text-sm leading-normal"><a class="text-white opacity-80"
                                href="javascript:;">Admin</a></li>
                        <li class="text-sm pl-2 capitalize leading-normal text-white before:float-left before:pr-2 before:text-white before:content-['/']"
                            aria-current="page">Overview</li>
                    </ol>
                    <h6 class="mb-0 font-bold text-white capitalize text-lg">Dashboard</h6>
                </nav>
                <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 justify-end">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold text-white">Welcome, Admin</span>
                        <div
                            class="h-10 w-10 rounded-full bg-white flex items-center justify-center text-green-600 font-bold shadow-sm">
                            A</div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- LOGOUT BUTTON (side) -->
        <div class="p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2.5 px-4 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>

        <div class="w-full px-6 py-6 mx-auto">

            <!-- STATISTICS CARDS -->
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 xl:mb-0 xl:w-1/4">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl bg-clip-border">
                        <div class="flex-auto p-4">
                            <div class="flex flex-row -mx-3">
                                <div class="flex-none w-2/3 max-w-full px-3">
                                    <p
                                        class="mb-0 font-sans text-sm font-semibold leading-normal uppercase text-slate-500">
                                        Total Farmers</p>
                                    <h5 class="mb-0 font-bold text-xl text-slate-700">{{ $stats['total_farmers'] ?? 0 }}
                                    </h5>
                                </div>
                                <div class="px-3 text-right basis-1/3">
                                    <div
                                        class="inline-block w-12 h-12 text-center rounded-full shadow-sm bg-gradient-to-tl from-blue-500 to-cyan-400">
                                        <i class="fas fa-users text-lg relative top-3.5 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 xl:mb-0 xl:w-1/4">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl bg-clip-border">
                        <div class="flex-auto p-4">
                            <div class="flex flex-row -mx-3">
                                <div class="flex-none w-2/3 max-w-full px-3">
                                    <p
                                        class="mb-0 font-sans text-sm font-semibold leading-normal uppercase text-slate-500">
                                        Active Fields</p>
                                    <h5 class="mb-0 font-bold text-xl text-slate-700">{{ $stats['active_fields'] ?? 0 }}
                                    </h5>
                                </div>
                                <div class="px-3 text-right basis-1/3">
                                    <div
                                        class="inline-block w-12 h-12 text-center rounded-full shadow-sm bg-gradient-to-tl from-green-500 to-teal-400">
                                        <i class="fas fa-map text-lg relative top-3.5 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 xl:mb-0 xl:w-1/4">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl bg-clip-border">
                        <div class="flex-auto p-4">
                            <div class="flex flex-row -mx-3">
                                <div class="flex-none w-2/3 max-w-full px-3">
                                    <p
                                        class="mb-0 font-sans text-xs font-semibold leading-normal uppercase text-slate-500">
                                        Est. Harvest (Tons)</p>
                                    <h5 class="mb-0 font-bold text-xl text-slate-700">
                                        {{ $stats['est_harvest_tons'] ?? 0 }}
                                    </h5>
                                </div>
                                <div class="px-3 text-right basis-1/3">
                                    <div
                                        class="inline-block w-12 h-12 text-center rounded-full shadow-sm bg-gradient-to-tl from-orange-500 to-yellow-400">
                                        <i class="fas fa-tractor text-lg relative top-3.5 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full max-w-full px-3 sm:w-1/2 xl:w-1/4">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white shadow-xl rounded-2xl bg-clip-border">
                        <div class="flex-auto p-4">
                            <div class="flex flex-row -mx-3">
                                <div class="flex-none w-2/3 max-w-full px-3">
                                    <p
                                        class="mb-0 font-sans text-xs font-semibold leading-normal uppercase text-slate-500">
                                        Avg Market Price</p>
                                    <h5 class="mb-0 font-bold text-xl text-slate-700">
                                        {{ $stats['latest_price'] ?? 'N/A' }}
                                    </h5>
                                </div>
                                <div class="px-3 text-right basis-1/3">
                                    <div
                                        class="inline-block w-12 h-12 text-center rounded-full shadow-sm bg-gradient-to-tl from-purple-600 to-purple-400">
                                        <i class="fas fa-chart-line text-lg relative top-3.5 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BUTTON TO OPEN PRICE MODAL -->
            <div class="flex justify-end mb-4">
                <button type="button" onclick="openPriceModal()"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-5 rounded-lg shadow transition flex items-center gap-2">
                    <i class="fas fa-coins"></i> Kelola Harga Komoditas
                </button>
            </div>

            <!-- RECENTLY MAPPED FIELDS TABLE (tidak berubah) -->
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 mt-0 mb-6">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                            <h6 class="mb-2 text-lg font-bold text-slate-700">Recently Mapped Fields</h6>
                        </div>
                        <div class="overflow-x-auto p-0 mt-4">
                            <table class="items-center w-full mb-0 align-top border-collapse text-slate-500">
                                <thead class="align-bottom">
                                    <tr>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Farmer</th>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Location</th>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Area (Ha)</th>
                                        <th
                                            class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Crop Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recent_mappings ?? [] as $mapping)
                                        <tr>
                                            <td
                                                class="p-2 align-middle bg-transparent border-b border-gray-100 whitespace-nowrap">
                                                <div class="flex px-4 py-1">
                                                    <div class="flex flex-col justify-center">
                                                        <h6
                                                            class="mb-0 text-sm leading-normal text-slate-700 font-semibold">
                                                            {{ $mapping['farmer'] }}
                                                        </h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                class="p-2 px-6 align-middle bg-transparent border-b border-gray-100 whitespace-nowrap">
                                                <p class="mb-0 text-sm font-semibold leading-tight">
                                                    {{ $mapping['location'] }}
                                                </p>
                                            </td>
                                            <td
                                                class="p-2 px-6 align-middle bg-transparent border-b border-gray-100 whitespace-nowrap">
                                                <span
                                                    class="text-sm font-semibold leading-tight text-slate-500">{{ $mapping['area_ha'] }}</span>
                                            </td>
                                            <td
                                                class="p-2 px-6 align-middle bg-transparent border-b border-gray-100 whitespace-nowrap">
                                                <span
                                                    class="bg-gradient-to-tl from-green-500 to-teal-400 px-3 py-1.5 text-xs rounded-1.8 py-2.2 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white rounded-lg">{{ $mapping['crop'] }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="p-6 text-center text-sm align-middle bg-transparent border-b border-gray-100 whitespace-nowrap text-slate-400">
                                                No recent mapping data found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- MODAL KELOLA HARGA KOMODITAS (RAPI, LEBAR KOLOM PAS) -->
    <div id="priceModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <div class="px-6 pt-5 pb-4 bg-white">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-lg font-bold text-gray-900" id="modal-title">Daftar Harga & Faktor Konversi</h3>
                        <button onclick="closePriceModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="mt-4">
                        <table class="w-full table-auto border-collapse">
                            <thead>
                                <tr>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b">
                                        Komoditas</th>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b">
                                        Harga (Rp/kg)</th>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b">
                                        Faktor Konversi</th>
                                    <th
                                        class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prices as $price)
                                    <form action="{{ route('admin.prices.update', $price->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                                            <td class="px-3 py-3 text-sm font-medium text-gray-800">{{ $price->commodity }}
                                            </td>
                                            <td class="px-3 py-2">
                                                <input type="number" name="price_per_kg" value="{{ $price->price_per_kg }}"
                                                    step="100"
                                                    class="w-28 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-green-500 focus:border-green-500">
                                            </td>
                                            <td class="px-3 py-2">
                                                <input type="number" name="conversion_factor"
                                                    value="{{ $price->conversion_factor }}" step="0.01"
                                                    class="w-20 px-2 py-1 border border-gray-300 rounded text-sm focus:ring-green-500 focus:border-green-500">
                                            </td>
                                            <td class="px-3 py-2">
                                                <button type="submit"
                                                    class="bg-green-600 hover:bg-green-700 text-white font-medium px-3 py-1 rounded text-sm transition">Simpan</button>
                                            </td>
                                        </tr>
                                    </form>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="px-6 py-3 bg-gray-50 text-right">
                    <button onclick="closePriceModal()"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openPriceModal() {
            document.getElementById('priceModal').classList.remove('hidden');
        }
        function closePriceModal() {
            document.getElementById('priceModal').classList.add('hidden');
        }
        // Tutup modal jika klik di luar area modal
        window.onclick = function (event) {
            const modal = document.getElementById('priceModal');
            if (event.target === modal) {
                closePriceModal();
            }
        }
    </script>

</body>

</html>