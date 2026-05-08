<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Map Tracker - TaniCheck</title>
    
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Leaflet & Geoman Plugins -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
    
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

    <!-- Dark Mode Overrides for Leaflet -->
    <style>
        body { margin: 0; padding: 0; }
        #map { height: calc(100vh - 73px); width: 100%; z-index: 10; } /* Adjusting for navbar height */
        
        /* Dark Popups */
        .leaflet-popup-content-wrapper, .leaflet-popup-tip {
            background-color: #18181b !important; /* zinc-900 */
            color: #e4e4e7 !important; /* zinc-200 */
            border: 1px solid #3f3f46; /* zinc-700 */
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
        }
        .leaflet-popup-content { margin: 12px; }
        .leaflet-container a.leaflet-popup-close-button { color: #a1a1aa; }
        .leaflet-container a.leaflet-popup-close-button:hover { color: #f87171; }

        /* Dark Tooltips */
        .leaflet-tooltip { 
            background: rgba(24, 24, 27, 0.9) !important; /* zinc-900 with opacity */
            color: #e4e4e7 !important; 
            border: 1px solid #3f3f46 !important; 
            border-radius: 8px !important; 
            padding: 8px 12px !important;
            font-weight: 600 !important;
            backdrop-filter: blur(4px);
        }
        .leaflet-tooltip-bottom:before { border-bottom-color: #3f3f46 !important; }
        .leaflet-tooltip-top:before { border-top-color: #3f3f46 !important; }
        
        /* Dark Geoman Toolbar */
        .leaflet-pm-toolbar .button-container a { background-color: #18181b; color: #d4d4d8; border-color: #3f3f46; }
        .leaflet-pm-toolbar .button-container a:hover { background-color: #27272a; color: #10b981; }
    </style>
</head>
<body class="bg-[#09090b] text-zinc-200 font-sans antialiased overflow-hidden flex flex-col min-h-screen">

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
                <a href="{{ route('admin.mapping') }}" class="px-4 py-2.5 rounded-lg text-sm font-semibold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 transition-colors">Fields</a>
                <a href="{{ route('admin.ubinans.index') }}" class="px-4 py-2.5 rounded-lg text-sm font-medium text-zinc-400 hover:text-zinc-200 hover:bg-zinc-800/60 transition-colors">Crops</a>
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

<!-- MAP CONTAINER -->
    <div id="map" class="flex-grow"></div>
    
    <script>
        // Set maxZoom to 24 so the user can scroll in way closer
        const map = L.map('map', { 
            zoomControl: false,
            maxZoom: 24 
        }).setView([-7.5, 110.0], 13);
        
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // Map Layers - Added maxNativeZoom and maxZoom properties
        // maxNativeZoom: 19 tells Leaflet "these photos stop at zoom 19"
        // maxZoom: 24 tells Leaflet "if they zoom past 19, just stretch the pixels up to 24"
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
            drawPolygon: false,
            editMode: true,
            removalMode: true,
        });

        const polygonGroup = L.layerGroup();
        const markerGroup = L.layerGroup();
        const ZOOM_THRESHOLD = 15;

        const landLayers = [];

        @foreach($lands as $land)
            @if($land->boundaries)
                (function() {
                    // --- SETUP POLYGON ---
                    const polygon = L.polygon({!! json_encode($land->boundaries) !!}, {
                        color: '#fbbf24', 
                        fillColor: '#fbbf24', 
                        fillOpacity: 0.3,
                        weight: 2
                    });

                    polygon.on('mouseover', function(e) {
                        this.setStyle({ fillOpacity: 0.6, weight: 4, color: '#10b981' });
                        this.bindTooltip(`
                            <div class="text-xs">
                                <p class="font-bold text-sm text-emerald-400">{{ $land->nickname }}</p>
                                <p class="text-zinc-300 mt-1"><span class="text-zinc-500">Petani:</span> {{ $land->user->name }}</p>
                                <p class="text-zinc-300"><span class="text-zinc-500">Luas:</span> {{ $land->area_size }} Ha</p>
                            </div>
                        `, { sticky: true }).openTooltip();
                    });

                    polygon.on('mouseout', function(e) {
                        this.setStyle({ fillOpacity: 0.3, weight: 2, color: '#fbbf24' });
                        this.closeTooltip();
                    });

                    // --- SETUP PIN (MARKER) ---
                    const marker = L.marker([{{ $land->lat }}, {{ $land->lng }}]);

                    // --- SETUP POPUP (Dark Styled) ---
                    const popupContent = `
                        <div class="p-1 min-w-[220px]">
                            <h3 class="text-base font-bold text-white mb-3 border-b border-zinc-700 pb-2">Edit Lahan</h3>
                            <form action="{{ route('admin.lands.update', $land->id) }}" method="POST" class="space-y-4">
                                @csrf @method('PUT')
                                <input type="hidden" name="lat" id="lat-{{ $land->id }}" value="{{ $land->lat }}">
                                <input type="hidden" name="lng" id="lng-{{ $land->id }}" value="{{ $land->lng }}">
                                <input type="hidden" name="boundaries" id="boundaries-{{ $land->id }}" value='{!! json_encode($land->boundaries) !!}'>

                                <div>
                                    <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1.5">Nama Lahan</label>
                                    <input type="text" name="nickname" value="{{ $land->nickname }}" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-sm text-white focus:ring-1 focus:ring-emerald-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1.5">Luas (Ha)</label>
                                    <input type="number" step="0.0001" name="area_size" id="area-{{ $land->id }}" value="{{ $land->area_size }}" class="w-full bg-zinc-800/50 border border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-300 outline-none" readonly>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wider mb-1.5">Pemilik</label>
                                    <select name="user_id" class="w-full bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-sm text-white outline-none">
                                        @foreach($farmers as $farmer)
                                            <option value="{{ $farmer->id }}" {{ $farmer->id == $land->user_id ? 'selected' : '' }}>{{ $farmer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white py-2 rounded-lg text-xs font-bold transition-all shadow-lg shadow-emerald-900/30">Simpan Perubahan</button>
                            </form>
                        </div>
                    `;
                    
                    polygon.bindPopup(popupContent);
                    marker.bindPopup(popupContent);

                    polygon.on('pm:edit', function() {
                        const latLngs = polygon.getLatLngs()[0];
                        const newBoundaries = latLngs.map(p => [p.lat, p.lng]);
                        const center = polygon.getBounds().getCenter();
                        const area = (turf.area(polygon.toGeoJSON()) / 10000).toFixed(4);

                        document.getElementById('boundaries-{{ $land->id }}').value = JSON.stringify(newBoundaries);
                        document.getElementById('lat-{{ $land->id }}').value = center.lat;
                        document.getElementById('lng-{{ $land->id }}').value = center.lng;
                        document.getElementById('area-{{ $land->id }}').value = area;
                        
                        marker.setLatLng(center); 
                    });

                    polygonGroup.addLayer(polygon);
                    markerGroup.addLayer(marker);
                    
                    landLayers.push(polygon); 
                })();
            @endif
        @endforeach

        // 3. Auto-fit bounds on load
        if (landLayers.length > 0) {
            const group = new L.featureGroup(landLayers);
            map.fitBounds(group.getBounds().pad(0.2));
        }

        // 4. Initial check
        if (map.getZoom() >= ZOOM_THRESHOLD) {
            polygonGroup.addTo(map);
        } else {
            markerGroup.addTo(map);
        }

        // 5. The Magic Listener
        map.on('zoomend', function() {
            const currentZoom = map.getZoom();
            
            if (currentZoom >= ZOOM_THRESHOLD) {
                if (map.hasLayer(markerGroup)) map.removeLayer(markerGroup);
                if (!map.hasLayer(polygonGroup)) polygonGroup.addTo(map);
            } else {
                if (map.hasLayer(polygonGroup)) map.removeLayer(polygonGroup);
                if (!map.hasLayer(markerGroup)) markerGroup.addTo(map);
            }
        });

    </script>
</body>
</html>