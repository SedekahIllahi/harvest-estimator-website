<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Pendaftaran Lahan Petani</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 20px; background: #f4f4f4; }
        .dashboard { background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        #map { height: 500px; width: 100%; border-radius: 12px; border: 2px solid #ccc; z-index: 1; }
        .btn { border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold; color: white; margin-right: 10px; }
        .btn-blue { background: #2563eb; }
        .btn-red { background: #dc2626; }
        .stats { font-size: 1.2rem; color: #16a34a; font-weight: bold; margin: 15px 0; }
    </style>
</head>
<body>

    <div class="dashboard">
        <h2>Pendaftaran Lahan Baru (Mode Admin)</h2>
        <p>1. Gunakan GPS saat berada di sawah. 2. Tap sudut sawah. 3. Simpan ke database.</p>
        
        <div>
            <button class="btn btn-blue" onclick="getLocation()">📍 Cari Lokasi Saya (GPS)</button>
            <button class="btn btn-red" onclick="resetMap()">🗑️ Reset Titik</button>
        </div>

        <div class="stats">Luas Estimasi: <span id="area-result">0</span> Hektar</div>
    </div>

    <div id="map"></div>

    <script>
        // 1. Initialize Map (Center of Java)
        const map = L.map('map').setView([-7.25, 110.0], 10);

        // 2. LAYER 1: The HD Satellite Ground
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19
        }).addTo(map);

        // 3. LAYER 2: The Transparent Text Labels (Cities, Streets)
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19
        }).addTo(map);

        let points = [];
        let markers = [];
        let polygonLayer = null;

        // 4. GPS Auto-Locator
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    // Fly to the user's literal location and zoom in hard (Level 18)
                    map.flyTo([lat, lng], 18);
                    
                    // Optional: Drop a temporary blue pin to show exactly where they are standing
                    L.marker([lat, lng]).bindPopup("Posisi Anda").addTo(map).openPopup();
                }, function(error) {
                    alert("GPS gagal: Pastikan Anda memberi izin lokasi di browser.");
                });
            } else {
                alert("Browser ini tidak mendukung fitur GPS.");
            }
        }

        // 5. Draw the Polygon (Yellow so it's visible on dark satellite)
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            points.push([lng, lat]); 
            const marker = L.marker([lat, lng]).addTo(map);
            markers.push(marker);

            drawShapeAndCalculate();
        });

        function drawShapeAndCalculate() {
            if (polygonLayer) map.removeLayer(polygonLayer);

            if (points.length >= 3) {
                let closedPoints = [...points, points[0]]; 
                let polygon = turf.polygon([closedPoints]);
                
                let areaSqm = turf.area(polygon);
                let areaHectares = (areaSqm / 10000).toFixed(4);

                document.getElementById('area-result').innerText = areaHectares;

                let leafletCoords = closedPoints.map(p => [p[1], p[0]]); 
                // Changed color to Yellow/Orange so it pops against dark trees
                polygonLayer = L.polygon(leafletCoords, {color: '#f59e0b', fillColor: '#fcd34d', fillOpacity: 0.4}).addTo(map);
            }
        }

        function resetMap() {
            points = [];
            markers.forEach(m => map.removeLayer(m));
            markers = [];
            if (polygonLayer) map.removeLayer(polygonLayer);
            document.getElementById('area-result').innerText = "0";
        }
    </script>
</body>
</html>