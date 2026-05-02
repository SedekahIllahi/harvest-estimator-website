<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Petani Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="max-w-6xl mx-auto py-10 px-4">
        <h1 class="text-3xl font-black text-gray-900 mb-8 uppercase tracking-tight">Tambah Petani & Lahan</h1>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                <p class="font-bold">Ada Kesalahan:</p>
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
                <form action="{{ route('admin.farmers.store') }}" method="POST" id="registrationForm">
                    @csrf
                    
                    <div class="space-y-4">
                        <h3 class="text-lg font-bold text-green-700 border-b pb-2">1. Data Akun Petani</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full mt-1 p-3 border rounded-xl bg-gray-50 focus:ring-2 focus:ring-green-500 outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="08..." required class="w-full mt-1 p-3 border rounded-xl bg-gray-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">PIN (6 Digit)</label>
                                <input type="password" name="pin" placeholder="123456" required class="w-full mt-1 p-3 border rounded-xl bg-gray-50">
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-green-700 border-b pb-2 pt-4">2. Data Lahan</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Sawah (e.g., Lahan Blok A)</label>
                            <input type="text" name="sawah_name" value="{{ old('sawah_name') }}" required class="w-full mt-1 p-3 border rounded-xl bg-gray-50">
                        </div>

                        <input type="hidden" name="area_hectares" id="input_area">
                        <input type="hidden" name="lat" id="input_lat">
                        <input type="hidden" name="lng" id="input_lng">
                        <input type="hidden" name="boundaries" id="boundaries">

                        <div class="bg-green-50 p-6 rounded-2xl border-2 border-dashed border-green-200 text-center">
                            <p class="text-xs text-green-600 font-bold uppercase tracking-widest">Luas Terhitung</p>
                            <p class="text-4xl font-black text-green-900"><span id="display_area">0</span> <span class="text-lg">Ha</span></p>
                        </div>

                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-extrabold py-5 rounded-2xl shadow-xl transition-all active:scale-95 text-lg">
                            SIMPAN DATA KE SISTEM
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-center px-2">
                    <button onclick="getLocation()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl font-bold text-sm shadow-md transition-all">📍 GPS SAYA</button>
                    <button onclick="resetMap()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-xl font-bold text-sm transition-all">🗑️ RESET PETA</button>
                </div>
                
                <div id="map" class="h-[550px] w-full rounded-3xl shadow-2xl border-4 border-white"></div>
                
                <div class="bg-white p-4 rounded-xl border border-gray-200 text-xs text-gray-500">
                    <strong>Cara Pakai:</strong> Cari lokasi sawah, klik pada setiap sudut batas lahan sampai membentuk bidang hijau/kuning. Luas akan otomatis terhitung.
                </div>
            </div>

        </div>
    </div>

<script>
        // --- MAP CONFIG ---
        const map = L.map('map', { zoomControl: false }).setView([-7.5, 110.0], 15);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // ArcGIS Layers
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}').addTo(map);
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}').addTo(map);
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}').addTo(map);

        // 1. Add the Geoman Toolbar
        map.pm.addControls({
            position: 'topleft',
            drawMarker: false,
            drawCircle: false,
            drawPolyline: false,
            drawRectangle: false,
            drawCircleMarker: false,
            drawText: false,
            editMode: true,
            dragMode: true, // Let them drag the whole shape if needed
            cutPolygon: false,
            removalMode: true,
        });

        // Variable to hold the current drawn layer
        let currentLayer = null;

        // --- CORE FUNCTION: Process the shape and update HTML inputs ---
        function processShape(layer) {
            // Get boundaries for the JSON array
            const latLngs = layer.getLatLngs()[0];
            const boundaryArray = latLngs.map(point => [point.lat, point.lng]);

            // Get center for the map pin anchor
            const bounds = layer.getBounds();
            const center = bounds.getCenter();

            // Calculate Area with Turf.js (Converts Leaflet shape to GeoJSON for the math)
            const geojson = layer.toGeoJSON();
            const areaSqMeters = turf.area(geojson);
            const areaHectares = (areaSqMeters / 10000).toFixed(4);

            // Update the hidden form inputs
            document.getElementById('boundaries').value = JSON.stringify(boundaryArray);
            document.getElementById('input_lat').value = center.lat;
            document.getElementById('input_lng').value = center.lng;
            document.getElementById('input_area').value = areaHectares;

            // Update the big green UI display
            document.getElementById('display_area').innerText = areaHectares;
        }

        // 2. Listen for when a shape is drawn
        map.on('pm:create', e => {
            // Prevent multiple fields (Delete old one if it exists)
            if (currentLayer) {
                map.removeLayer(currentLayer);
            }
            
            currentLayer = e.layer;

            // Run the math immediately
            processShape(currentLayer);

            // Add listeners so if the admin edits or drags the shape later, the area updates live
            currentLayer.on('pm:edit', () => processShape(currentLayer));
            currentLayer.on('pm:dragend', () => processShape(currentLayer));
        });

        // 3. Clear inputs if the shape is deleted via the toolbar
        map.on('pm:remove', e => {
            if (e.layer === currentLayer) {
                clearFormAndUI();
            }
        });

        // --- BUTTON ACTIONS ---

        function clearFormAndUI() {
            currentLayer = null;
            document.getElementById('boundaries').value = '';
            document.getElementById('input_lat').value = '';
            document.getElementById('input_lng').value = '';
            document.getElementById('input_area').value = '';
            document.getElementById('display_area').innerText = "0";
        }

        function resetMap() {
            // Remove the shape from the map if it exists
            if (currentLayer) {
                map.removeLayer(currentLayer);
            }
            // Clear all the UI and hidden inputs
            clearFormAndUI();
        }

        function getLocation() {
            if (!navigator.geolocation) return alert("Browser tidak support GPS");
            
            navigator.geolocation.getCurrentPosition(pos => {
                map.flyTo([pos.coords.latitude, pos.coords.longitude], 18);
                // Drop a tiny temporary pin to show where they are
                L.marker([pos.coords.latitude, pos.coords.longitude])
                 .addTo(map)
                 .bindPopup("Lokasi Anda")
                 .openPopup();
            });
        }
    </script>
</body>
</html>