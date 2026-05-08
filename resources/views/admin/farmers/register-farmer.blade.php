<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Petani Baru - TaniCheck</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Leaflet & Geoman Plugins -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>

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

    <!-- Geoman Dark Toolbar Style Overrides -->
    <style>
        .leaflet-pm-toolbar .button-container a {
            background-color: #18181b;
            color: #d4d4d8;
            border-color: #3f3f46;
        }

        .leaflet-pm-toolbar .button-container a:hover {
            background-color: #27272a;
            color: #10b981;
        }
    </style>
</head>

<body class="bg-[#09090b] font-sans antialiased text-zinc-200 min-h-screen">

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
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800/60 transition-colors">Dashboard</a>
                <a href="{{ route('admin.mapping') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800/60 transition-colors">Fields</a>
                <a href="{{ route('admin.ubinans.index') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800/60 transition-colors">Crops</a>
                <!-- Farmers is active -->
                <a href="{{ route('admin.farmers.index') }}" class="px-4 py-2.5 rounded-lg text-sm font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 transition-colors">Farmers</a>
            </div>

            <div class="flex items-center gap-3 sm:gap-4">
                <div class="w-9 h-9 rounded-full bg-zinc-800 flex items-center justify-center text-xs font-bold text-zinc-300 ring-2 ring-zinc-700/50 cursor-pointer hover:ring-zinc-600 transition-all">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A',0,2)) }}
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 sm:py-10 px-4 sm:px-6 lg:px-8">

        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-heading text-3xl font-bold text-white tracking-tight">Tambah Petani & Lahan</h1>
                <p class="text-sm font-medium text-zinc-400 mt-1">Register a new farmer and instantly map their primary field boundaries.</p>
            </div>
            <a href="{{ route('admin.farmers.index') }}" class="text-zinc-400 hover:text-white font-semibold transition-colors flex items-center gap-2 bg-zinc-800/50 hover:bg-zinc-800 px-4 py-2 rounded-lg border border-zinc-700/50">
                &larr; Batal
            </a>
        </div>

        @if(session('success'))
        <div class="bg-emerald-500/10 border-l-4 border-emerald-500 text-emerald-400 p-4 mb-6 rounded-xl shadow-sm">
            <p class="font-bold">Berhasil!</p>
            <p>{{ session('success') }}</p>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-500/10 border-l-4 border-red-500 text-red-400 p-4 mb-6 rounded-xl shadow-sm">
            <p class="font-bold">Ada Kesalahan:</p>
            <ul class="list-disc ml-5 mt-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- FORM COLUMN -->
            <div class="bg-zinc-900/80 p-6 sm:p-8 rounded-2xl shadow-xl shadow-black/20 border border-zinc-800/60 h-fit">
                <form action="{{ route('admin.farmers.store') }}" method="POST" id="registrationForm">
                    @csrf

                    <div class="space-y-6">

                        <!-- Account Details -->
                        <div>
                            <h3 class="text-sm font-bold text-emerald-400 border-b border-zinc-700/50 pb-3 mb-4 uppercase tracking-wider"><i class="fas fa-user mr-2"></i> 1. Data Akun Petani</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-zinc-400 mb-1.5 uppercase">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full p-3 border border-zinc-700 rounded-xl bg-zinc-900 text-white placeholder-zinc-600 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all">
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-zinc-400 mb-1.5 uppercase">Nomor HP</label>
                                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="08..." required class="w-full p-3 border border-zinc-700 rounded-xl bg-zinc-900 text-white placeholder-zinc-600 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-zinc-400 mb-1.5 uppercase">PIN (6 Digit)</label>
                                        <input type="password" name="pin" placeholder="123456" required class="w-full p-3 border border-zinc-700 rounded-xl bg-zinc-900 text-white placeholder-zinc-600 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Land Details -->
                        <div class="pt-2">
                            <h3 class="text-sm font-bold text-emerald-400 border-b border-zinc-700/50 pb-3 mb-4 uppercase tracking-wider"><i class="fas fa-map mr-2"></i> 2. Data Lahan</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-zinc-400 mb-1.5 uppercase">Nama Sawah (e.g., Lahan Blok A)</label>
                                    <input type="text" name="sawah_name" value="{{ old('sawah_name') }}" required class="w-full p-3 border border-zinc-700 rounded-xl bg-zinc-900 text-white placeholder-zinc-600 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 outline-none transition-all">
                                </div>

                                <!-- HIDDEN GIS INPUTS -->
                                <input type="hidden" name="area_hectares" id="input_area">
                                <input type="hidden" name="lat" id="input_lat">
                                <input type="hidden" name="lng" id="input_lng">
                                <input type="hidden" name="boundaries" id="boundaries">

                                <!-- DYNAMIC AREA DISPLAY -->
                                <div class="bg-emerald-500/10 p-6 rounded-2xl border border-emerald-500/30 text-center shadow-inner mt-6">
                                    <p class="text-xs text-emerald-500 font-bold uppercase tracking-widest mb-1">Luas Terhitung</p>
                                    <p class="font-heading text-4xl font-black text-emerald-400"><span id="display_area">0</span> <span class="text-lg font-bold text-emerald-500/70">Meter Persegi</span></p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold py-4 rounded-xl shadow-lg shadow-emerald-900/30 transition-all flex justify-center items-center gap-2 mt-4">
                            <i class="fas fa-save"></i> SIMPAN DATA KE SISTEM
                        </button>
                    </div>
                </form>
            </div>

            <!-- MAP COLUMN -->
            <div class="space-y-4">

                <!-- Map Tools -->
                <div class="flex justify-between items-center px-1">
                    <button onclick="getLocation()" class="bg-sky-600 hover:bg-sky-500 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md transition-all flex items-center gap-2">
                        <i class="fas fa-location-arrow"></i> GPS SAYA
                    </button>
                    <button onclick="resetMap()" class="bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-400 px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
                        <i class="fas fa-trash-alt"></i> RESET PETA
                    </button>
                </div>

                <!-- The Map -->
                <div id="map" class="h-[500px] sm:h-[550px] w-full rounded-2xl shadow-xl shadow-black/20 border-2 border-zinc-800/60 z-10 relative"></div>

                <!-- Instructions -->
                <div class="bg-zinc-900/80 p-4 rounded-xl border border-zinc-800/60 flex gap-3 items-start">
                    <i class="fas fa-info-circle text-sky-400 mt-0.5 text-lg"></i>
                    <p class="text-sm text-zinc-400 leading-relaxed">
                        <strong class="text-zinc-200">Cara Pakai:</strong> Cari lokasi sawah di peta. Gunakan tool polygon di kiri atas, lalu klik pada setiap sudut batas lahan sampai membentuk bidang tertutup. Luas lahan akan otomatis terhitung di form sebelah kiri.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <script>
        // 1. Added maxZoom: 24 so users can scroll in much closer
        const map = L.map('map', {
            zoomControl: false,
            maxZoom: 24
        }).setView([-7.5, 110.0], 15);

        L.control.zoom({
            position: 'bottomright'
        }).addTo(map);

        // 2. Added maxNativeZoom and maxZoom to all tile layers
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxNativeZoom: 18,
            maxZoom: 24
        }).addTo(map);

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}', {
            maxNativeZoom: 18,
            maxZoom: 24
        }).addTo(map);

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            maxNativeZoom: 18,
            maxZoom: 24
        }).addTo(map);

        map.pm.addControls({
            position: 'topleft',
            drawMarker: false,
            drawCircle: false,
            drawPolyline: false,
            drawRectangle: false,
            drawCircleMarker: false,
            drawText: false,
            editMode: true,
            dragMode: true,
            cutPolygon: false,
            removalMode: true,
        });

        let currentLayer = null;

        map.pm.setGlobalOptions({ tooltips: false });

        function processShape(layer) {
            const latLngs = layer.getLatLngs()[0];
            const boundaryArray = latLngs.map(point => [point.lat, point.lng]);

            const bounds = layer.getBounds();
            const center = bounds.getCenter();

            // 3. THE MATH FIX: Turf natively calculates in square meters. 
            // We just format it to 2 decimal places and stop dividing by 10000.
            const geojson = layer.toGeoJSON();
            const areaSqMeters = turf.area(geojson).toFixed(2);

            document.getElementById('boundaries').value = JSON.stringify(boundaryArray);
            document.getElementById('input_lat').value = center.lat;
            document.getElementById('input_lng').value = center.lng;

            // 4. Send the m² value to your inputs
            document.getElementById('input_area').value = areaSqMeters;
            document.getElementById('display_area').innerText = areaSqMeters;
        }

        map.on('pm:create', e => {
            if (currentLayer) {
                map.removeLayer(currentLayer);
            }
            currentLayer = e.layer;

            // Apply custom styling for drawn layers to fit the dark mode vibe
            currentLayer.setStyle({
                color: '#10b981',
                fillColor: '#10b981',
                fillOpacity: 0.4
            });

            processShape(currentLayer);

            currentLayer.on('pm:edit', () => processShape(currentLayer));
            currentLayer.on('pm:dragend', () => processShape(currentLayer));
        });

        map.on('pm:remove', e => {
            if (e.layer === currentLayer) {
                clearFormAndUI();
            }
        });

        function clearFormAndUI() {
            currentLayer = null;
            document.getElementById('boundaries').value = '';
            document.getElementById('input_lat').value = '';
            document.getElementById('input_lng').value = '';
            document.getElementById('input_area').value = '';
            document.getElementById('display_area').innerText = "0";
        }

        function resetMap() {
            if (currentLayer) {
                map.removeLayer(currentLayer);
            }
            clearFormAndUI();
        }

        function getLocation() {
            if (!navigator.geolocation) return alert("Browser tidak support GPS");
            navigator.geolocation.getCurrentPosition(pos => {
                // You can now change this 18 to a 20 or 21 since we bumped the max zoom!
                map.flyTo([pos.coords.latitude, pos.coords.longitude], 20);

                // Custom dark marker for GPS location
                const gpsIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: "<div style='background-color:#0ea5e9;width:15px;height:15px;border-radius:50%;border:2px solid white;box-shadow:0 0 10px rgba(14,165,233,0.8);'></div>",
                    iconSize: [15, 15],
                    iconAnchor: [7, 7]
                });
                L.marker([pos.coords.latitude, pos.coords.longitude], {
                    icon: gpsIcon
                }).addTo(map);
            });
        }
    </script>
</body>

</html>