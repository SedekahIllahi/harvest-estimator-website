<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Map Tracker - TaniCheck</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css" />
    <script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
    
    <style>
        #map { height: calc(100vh - 64px); width: 100%; }
        .leaflet-tooltip { 
            background: rgba(0, 0, 0, 0.8); 
            color: white; 
            border: none; 
            border-radius: 8px; 
            padding: 8px 12px;
            font-weight: 600;
        }
        .leaflet-tooltip-bottom:before { border-bottom-color: rgba(0, 0, 0, 0.8); }
    </style>
</head>
<body class="bg-gray-100 overflow-hidden">

    <header class="h-16 bg-white border-b flex items-center justify-between px-6 shadow-sm z-50 relative">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-green-600 font-bold transition">&larr; Dashboard</a>
            <h1 class="text-xl font-bold text-gray-800 border-l pl-4">Master Map Tracker</h1>
        </div>
        @if(session('success'))
            <div class="bg-green-500 text-white px-4 py-1 rounded-full text-sm font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif
    </header>

    <div id="map"></div>
    
    <script>
        const map = L.map('map', { zoomControl: false }).setView([-7.5, 110.0], 13);
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}').addTo(map);
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Transportation/MapServer/tile/{z}/{y}/{x}').addTo(map);
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}').addTo(map);

        map.pm.addControls({
            position: 'topleft',
            drawMarker: false,
            drawPolygon: false,
            editMode: true,
            removalMode: true,
        });

        // 1. Create the two separate folders (Layer Groups)
        const polygonGroup = L.layerGroup();
        const markerGroup = L.layerGroup();
        const ZOOM_THRESHOLD = 15; // The zoom level where shapes turn into pins

        const landLayers = []; // For calculating the initial auto-zoom

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
                        this.setStyle({ fillOpacity: 0.6, weight: 4, color: '#16a34a' });
                        this.bindTooltip(`
                            <div class="text-xs">
                                <p class="font-bold text-sm">{{ $land->nickname }}</p>
                                <p>Petani: {{ $land->user->name }}</p>
                                <p>Luas: {{ $land->area_size }} Ha</p>
                            </div>
                        `, { sticky: true }).openTooltip();
                    });

                    polygon.on('mouseout', function(e) {
                        this.setStyle({ fillOpacity: 0.3, weight: 2, color: '#fbbf24' });
                        this.closeTooltip();
                    });

                    // --- SETUP PIN (MARKER) ---
                    // We use the exact center lat/lng from the database to drop the pin
                    const marker = L.marker([{{ $land->lat }}, {{ $land->lng }}]);

                    // --- SETUP POPUP (Shared between both) ---
                    const popupContent = `
                        <div class="p-2 min-w-[200px]">
                            <h3 class="text-lg font-bold text-gray-800 mb-1">Edit Lahan</h3>
                            <form action="{{ route('admin.lands.update', $land->id) }}" method="POST" class="space-y-3">
                                @csrf @method('PUT')
                                <input type="hidden" name="lat" id="lat-{{ $land->id }}" value="{{ $land->lat }}">
                                <input type="hidden" name="lng" id="lng-{{ $land->id }}" value="{{ $land->lng }}">
                                <input type="hidden" name="boundaries" id="boundaries-{{ $land->id }}" value='{!! json_encode($land->boundaries) !!}'>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase">Nama Lahan</label>
                                    <input type="text" name="nickname" value="{{ $land->nickname }}" class="w-full border rounded px-2 py-1 text-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase">Luas (Ha)</label>
                                    <input type="number" step="0.0001" name="area_size" id="area-{{ $land->id }}" value="{{ $land->area_size }}" class="w-full border rounded px-2 py-1 text-sm bg-gray-50">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase">Pemilik</label>
                                    <select name="user_id" class="w-full border rounded px-2 py-1 text-sm">
                                        @foreach($farmers as $farmer)
                                            <option value="{{ $farmer->id }}" {{ $farmer->id == $land->user_id ? 'selected' : '' }}>{{ $farmer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="w-full bg-green-600 text-white py-1.5 rounded text-xs font-bold">Simpan Perubahan</button>
                            </form>
                        </div>
                    `;
                    
                    // Bind the same edit form to both the shape and the pin
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
                        
                        // Move the invisible pin to the new center just in case they zoom out later
                        marker.setLatLng(center); 
                    });

                    // 2. Add them to their respective groups instead of directly to the map
                    polygonGroup.addLayer(polygon);
                    markerGroup.addLayer(marker);
                    
                    // Keep this for the auto-zoom calculation
                    landLayers.push(polygon); 
                })();
            @endif
        @endforeach

        // 3. Auto-fit bounds on load
        if (landLayers.length > 0) {
            const group = new L.featureGroup(landLayers);
            map.fitBounds(group.getBounds().pad(0.2));
        }

        // 4. Initial check: Are we zoomed in or out when the page loads?
        if (map.getZoom() >= ZOOM_THRESHOLD) {
            polygonGroup.addTo(map);
        } else {
            markerGroup.addTo(map);
        }

        // 5. The Magic Listener: Swap layers when the admin scrolls the mouse wheel
        map.on('zoomend', function() {
            const currentZoom = map.getZoom();
            
            if (currentZoom >= ZOOM_THRESHOLD) {
                // Zoomed IN: Hide pins, show shapes
                if (map.hasLayer(markerGroup)) map.removeLayer(markerGroup);
                if (!map.hasLayer(polygonGroup)) polygonGroup.addTo(map);
            } else {
                // Zoomed OUT: Hide shapes, show pins
                if (map.hasLayer(polygonGroup)) map.removeLayer(polygonGroup);
                if (!map.hasLayer(markerGroup)) markerGroup.addTo(map);
            }
        });

    </script>

</body>
</html>