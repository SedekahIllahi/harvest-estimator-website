<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiPanen – Dashboard Petani</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #eef3ec;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 0;
        }

        .phone-shell {
            width: 390px;
            min-height: 844px;
            background: #f5f8f3;
            position: relative;
            padding-bottom: 90px;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.02), 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        /* TOP BAR */
        .topbar {
            background: #1e3a1e;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 0 0 24px 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .topbar-logo {
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(135deg, #fff, #e0f0e0);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .topbar-bell {
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }

        .topbar-bell svg {
            width: 20px;
            height: 20px;
            stroke: #fff;
        }

        /* SCROLL AREA */
        .scroll-area {
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* STAT CARDS */
        .stat-card {
            background: white;
            border-radius: 24px;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03), 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            border: 1px solid rgba(0, 0, 0, 0.02);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(45, 74, 45, 0.08);
        }

        .stat-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #6b8a6b;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: #1a2e1a;
            line-height: 1.2;
        }

        .stat-delta {
            font-size: 12px;
            font-weight: 600;
            color: #3e9c5e;
            background: #eaf6ef;
            display: inline-block;
            padding: 2px 8px;
            border-radius: 40px;
            width: fit-content;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #f0f4ef;
        }

        .stat-icon.purple {
            background: linear-gradient(145deg, #e2d9ff, #d0c4fc);
        }

        .stat-icon.orange {
            background: linear-gradient(145deg, #fff0e0, #ffe0c4);
        }

        .stat-icon svg {
            width: 28px;
            height: 28px;
            fill: none;
            stroke: #2d4a2d;
            stroke-width: 1.8;
        }

        /* CHART CARD */
        .chart-card {
            background: white;
            border-radius: 28px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.02);
            border: 1px solid #eef2ec;
        }

        .chart-title {
            font-size: 16px;
            font-weight: 800;
            color: #1e2e1e;
        }

        .chart-sub {
            font-size: 11px;
            color: #8aa78a;
            margin-top: 4px;
            margin-bottom: 20px;
        }

        .chart-svg-wrap svg {
            width: 100%;
            height: 140px;
        }

        .chart-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
            padding: 0 4px;
        }

        .chart-labels span {
            font-size: 10px;
            font-weight: 500;
            color: #8aa78a;
        }

        /* TOTAL CARD - PERBAIKAN RINGKASAN PANEN */
        .total-card {
            background: white;
            border-radius: 28px;
            padding: 24px 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.02);
            border: 1px solid #eef2ec;
        }

        .total-title {
            font-size: 16px;
            font-weight: 800;
            color: #1e2e1e;
            margin-bottom: 18px;
            padding-bottom: 8px;
            border-bottom: 2px solid #eef2ec;
        }

        .total-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        .total-item {
            flex: 1;
            min-width: 110px;
            background: #fafcfa;
            border-radius: 20px;
            padding: 12px 10px;
            transition: all 0.2s;
        }

        .total-item:hover {
            background: #f0f5ef;
            transform: translateY(-2px);
        }

        .total-item-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #9bb69b;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: block;
        }

        .total-item-value {
            font-size: 18px;
            font-weight: 800;
            color: #1a2e1a;
            line-height: 1.3;
            word-break: break-word;
        }

        .flag-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
        }

        .flag {
            font-size: 22px;
        }

        /* BOTTOM NAV */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 390px;
            background: #ffffffdd;
            backdrop-filter: blur(12px);
            border-top: 1px solid #e5ebe3;
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 12px 24px 24px;
            z-index: 100;
            border-radius: 28px 28px 0 0;
        }

        .nav-icon {
            width: 48px;
            height: 48px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ecf3e9;
            transition: all 0.2s;
        }

        .nav-icon.active {
            background: #2d4a2d;
            box-shadow: 0 4px 8px rgba(45, 74, 45, 0.2);
        }

        .nav-icon svg {
            width: 22px;
            height: 22px;
            stroke: #5c7a5c;
        }

        .nav-icon.active svg {
            stroke: white;
        }

        /* HORIZONTAL SLIDER WRAPPER */
        .price-carousel {
            display: flex;
            overflow-x: auto;
            gap: 16px;
            padding-bottom: 8px;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            /* Firefox */
            margin: 0 -16px;
            /* Bleed to edges */
            padding: 0 16px 8px 16px;
            /* Keep inner padding */
        }

        .price-carousel::-webkit-scrollbar {
            display: none;
            /* Hide scrollbar for clean look */
        }

        /* CAROUSEL CARDS */
        .price-carousel .stat-card {
            min-width: 85%;
            /* 85% width lets the next card peek out, hinting they can scroll */
            scroll-snap-align: center;
            flex-shrink: 0;
        }

        /* DYNAMIC DELTA COLORS */
        .stat-delta.up {
            color: #3e9c5e;
            background: #eaf6ef;
        }

        .stat-delta.down {
            color: #d53f3f;
            background: #fcebeb;
        }

        .stat-delta.flat {
            color: #6b8a6b;
            background: #eef2ec;
        }

        /* BAR CHART STYLING */
        .bar-chart-container {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 150px;
            /* Chart height */
            padding-top: 10px;
            margin-bottom: 10px;
            border-bottom: 2px solid #eef2ec;
        }

        .bar-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            height: 100%;
            width: 100%;
        }

        .bar-value {
            font-size: 11px;
            font-weight: 800;
            color: #1a2e1a;
            margin-bottom: 8px;
            opacity: 0;
            transform: translateY(5px);
            animation: slideUp 0.3s forwards 0.4s;
        }

        .bar {
            width: 32px;
            background: linear-gradient(180deg, #3e9c5e 0%, #2d4a2d 100%);
            border-radius: 6px 6px 0 0;
            box-shadow: 0 4px 10px rgba(62, 156, 94, 0.2);
            /* Initial state for animation */
            height: 0%;
            animation: growBar 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes growBar {
            to {
                height: var(--target-height);
            }
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="phone-shell">

        <div class="topbar">
            <span class="topbar-logo">SiPanen</span>
            <div class="topbar-bell">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                </svg>
            </div>
        </div>

        <div class="scroll-area">

            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-label" id="weather-desc">Memuat Cuaca...</span>
                    <span class="stat-value" id="weather-temp">--°C</span>
                    <span class="stat-delta" id="weather-location">Sinkronisasi GPS...</span>
                </div>
                <div class="stat-icon purple flex items-center justify-center">
                    <i id="weather-icon" class="fas fa-spinner fa-spin" style="font-size: 24px; color: white;"></i>
                </div>
            </div>

            <div class="price-carousel">
                {{-- Assuming your controller passes a $cropPrices collection/array --}}
                @foreach($cropPrices as $crop)
                @php
                // The Math (Prevent division by zero just in case)
                $diff = $crop->current_price - $crop->last_price;
                $percent = $crop->last_price > 0 ? round(($diff / $crop->last_price) * 100, 1) : 0;

                // The Logic
                $isUp = $percent > 0;
                $isDown = $percent < 0;

                    // The Styling
                    $deltaClass=$isUp ? 'up' : ($isDown ? 'down' : 'flat' );
                    $sign=$isUp ? '+' : '' ; // Negative already has a minus sign
                    @endphp

                    <div class="stat-card">
                    <div class="stat-info">
                        <span class="stat-label">Harga {{ $crop->name }}</span>
                        <span class="stat-value">Rp {{ number_format($crop->current_price, 0, ',', '.') }}</span>
                        <span class="stat-delta {{ $deltaClass }}">
                            {{ $sign }}{{ $percent }}% dari harga terakhir
                        </span>
                    </div>
                    <div class="stat-icon orange">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                            <path d="M3 6h18" />
                            <path d="M16 10a4 4 0 01-8 0" />
                        </svg>
                    </div>
            </div>
            @endforeach
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <span class="stat-label">Perkiraan Panen</span>
                <span class="stat-value">{{ $estimasiRange }}</span>
                <span class="stat-delta">Berdasarkan {{ number_format($totalLuas, 2) }} Ha lahan</span>
            </div>
            <div class="stat-icon orange">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M12 2a10 10 0 100 20 10 10 0 000-20z" />
                    <path d="M12 6v6l4 2" />
                </svg>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-title">Riwayat Hasil Ubinan</div>
            <div class="chart-sub">Perbandingan estimasi panen (kg) terakhir</div>

            <div class="bar-chart-container">
                @php
                // Find the max value to calculate bar heights. Prevent division by zero.
                $maxVal = max($chartValues) ?: 1;
                @endphp

                @foreach($chartValues as $i => $val)
                @php
                // Calculate percentage height based on the maximum value
                $heightPct = ($val / $maxVal) * 100;
                // Give it a minimum 5% height so the bar isn't invisible if value is 0
                if($heightPct < 5) $heightPct=5;
                    @endphp

                    <div class="bar-wrapper">
                    <span class="bar-value">{{ $val > 0 ? number_format($val, 0, ',', '.') : '-' }}</span>
                    <div class="bar" style="--target-height: {{ $heightPct }}%;"></div>
            </div>
            @endforeach
        </div>

        <div class="chart-labels">
            @foreach($chartLabels as $label)
            <span>{{ $label }}</span>
            @endforeach
        </div>
    </div>

    <div class="total-card">
        <div class="total-title">Ringkasan Panen</div>
        <div class="total-stats">
            <div class="total-item">
                <span class="total-item-label">Lokasi</span>
                <div class="flag-wrap">
                    <span class="flag">{{ $totalPanenData['flag'] }}</span>
                    <span class="total-item-value" style="font-size: 14px;">{{ $totalPanenData['country'] }}</span>
                </div>
            </div>
            <div class="total-item">
                <span class="total-item-label">Total (kg)</span>
                <div class="total-item-value">{{ $totalPanenData['sales'] }}</div>
            </div>
            <div class="total-item">
                <span class="total-item-label">Nilai</span>
                <div class="total-item-value">{{ $totalPanenData['value'] }}</div>
            </div>
            <div class="total-item">
                <span class="total-item-label">Kenaikan</span>
                <div class="total-item-value">{{ $totalPanenData['bounce'] }}</div>
            </div>
        </div>
    </div>

    </div>
    </div>

    <div class="bottom-nav">
        <a href="{{ route('farmer.dashboard') }}">
            <div class="nav-icon active">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
            </div>
        </a>
        <a href="{{ route('farmer.kalkulator') }}">
            <div class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="3" width="20" height="14" rx="2" />
                    <line x1="8" y1="21" x2="16" y2="21" />
                    <line x1="12" y1="17" x2="12" y2="21" />
                </svg>
            </div>
        </a>
        <a href="{{ route('farmer.riwayat') }}">
            <div class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="8" y1="6" x2="21" y2="6" />
                    <line x1="8" y1="12" x2="21" y2="12" />
                    <line x1="8" y1="18" x2="21" y2="18" />
                    <line x1="3" y1="6" x2="3.01" y2="6" />
                    <line x1="3" y1="12" x2="3.01" y2="12" />
                    <line x1="3" y1="18" x2="3.01" y2="18" />
                </svg>
            </div>
        </a>
        <a href="{{ route('farmer.profile') }}">
            <div class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </div>
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            getWeather();
        });

        const weatherCodes = {
            0: {
                label: 'Cerah',
                icon: 'fa-sun'
            },
            1: {
                label: 'Cerah Berawan',
                icon: 'fa-cloud-sun'
            },
            2: {
                label: 'Berawan',
                icon: 'fa-cloud-sun'
            },
            3: {
                label: 'Mendung',
                icon: 'fa-cloud'
            },
            45: {
                label: 'Berkabut',
                icon: 'fa-smog'
            },
            48: {
                label: 'Kabut Tebal',
                icon: 'fa-smog'
            },
            51: {
                label: 'Gerimis Ringan',
                icon: 'fa-cloud-rain'
            },
            53: {
                label: 'Gerimis',
                icon: 'fa-cloud-rain'
            },
            61: {
                label: 'Hujan Ringan',
                icon: 'fa-cloud-rain'
            },
            63: {
                label: 'Hujan Sedang',
                icon: 'fa-cloud-showers-heavy'
            },
            65: {
                label: 'Hujan Lebat',
                icon: 'fa-cloud-showers-heavy'
            },
            95: {
                label: 'Hujan Badai',
                icon: 'fa-bolt'
            },
        };

        function fetchWeatherData(lat, lng) {
            const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lng}&current=temperature_2m,weather_code&timezone=auto`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    const current = data.current;
                    const codeInfo = weatherCodes[current.weather_code] || {
                        label: 'Berawan',
                        icon: 'fa-cloud'
                    };

                    document.getElementById('weather-temp').innerText = `${Math.round(current.temperature_2m)}°C`;
                    document.getElementById('weather-desc').textContent = codeInfo.label;
                    document.getElementById('weather-icon').className = `fas ${codeInfo.icon}`;
                    document.getElementById('weather-location').textContent = "Lokasi Saat Ini";
                })
                .catch(err => {
                    document.getElementById('weather-temp').innerText = "--°C";
                    document.getElementById('weather-desc').textContent = "Gagal memuat";
                    document.getElementById('weather-icon').className = "fas fa-exclamation-triangle";
                    document.getElementById('weather-location').textContent = "Offline";
                });
        }

        function getWeather() {
            // Default center if user denies GPS (Central Java)
            const fallbackLat = -7.5;
            const fallbackLng = 110.0;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (pos) => fetchWeatherData(pos.coords.latitude, pos.coords.longitude),
                    (err) => fetchWeatherData(fallbackLat, fallbackLng), {
                        timeout: 5000
                    }
                );
            } else {
                fetchWeatherData(fallbackLat, fallbackLng);
            }
        }
    </script>
</body>