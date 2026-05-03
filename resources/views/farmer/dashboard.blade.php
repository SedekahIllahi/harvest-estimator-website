<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiKAPAN – Dashboard Petani</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
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
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
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
    </style>
</head>

<body>
    <div class="phone-shell">

        <!-- TOP BAR -->
        <div class="topbar">
            <span class="topbar-logo">SiKAPAN</span>
            <div class="topbar-bell">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                </svg>
            </div>
        </div>

        <div class="scroll-area">

            <!-- CARD CUACA -->
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-label">Perkiraan Cuaca</span>
                    <span class="stat-value">{{ $weatherTemp }}</span>
                    <span class="stat-delta">{{ $weatherDelta }}</span>
                </div>
                <div class="stat-icon purple">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M12 2v2M4.93 4.93l1.41 1.41M2 12h2M4.93 19.07l1.41-1.41M12 20v2M19.07 19.07l-1.41-1.41M22 12h-2M19.07 4.93l-1.41 1.41M12 6a6 6 0 100 12 6 6 0 000-12z" />
                    </svg>
                </div>
            </div>

            <!-- HARGA JUAL -->
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-label">Harga Jual Hari Ini</span>
                    <span class="stat-value">{{ $hargaJual }}</span>
                    <span class="stat-delta">+5.2% dari bulan lalu</span>
                </div>
                <div class="stat-icon orange">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                        <path d="M3 6h18" />
                        <path d="M16 10a4 4 0 01-8 0" />
                    </svg>
                </div>
            </div>

            <!-- PERKIRAAN PANEN -->
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

            <!-- CHART PENJUALAN -->
            <div class="chart-card">
                <div class="chart-title">Grafik Penjualan</div>
                <div class="chart-sub">Total panen (kg) per bulan</div>
                <div class="chart-svg-wrap">
                    <svg viewBox="0 0 320 130" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="areaGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#7c6fcf" stop-opacity="0.35" />
                                <stop offset="100%" stop-color="#7c6fcf" stop-opacity="0.02" />
                            </linearGradient>
                        </defs>
                        <line x1="0" y1="30" x2="320" y2="30" stroke="#e2e8e0" stroke-width="1.2" />
                        <line x1="0" y1="60" x2="320" y2="60" stroke="#e2e8e0" stroke-width="1.2" />
                        <line x1="0" y1="90" x2="320" y2="90" stroke="#e2e8e0" stroke-width="1.2" />
                        @php
                            $maxVal = max($chartValues) ?: 1;
                            $points = [];
                            $xStep = 320 / (count($chartValues) - 1);
                            foreach ($chartValues as $i => $val) {
                                $y = 110 - ($val / $maxVal) * 90;
                                $x = $i * $xStep;
                                $points[] = "$x $y";
                            }
                            $polyline = implode(' L ', $points);
                        @endphp
                        <path d="M0 110 L {{ $polyline }} L 320 110 Z" fill="url(#areaGrad)" />
                        <polyline points="{{ $polyline }}" stroke="#7c6fcf" stroke-width="2.5" fill="none" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="chart-labels">
                    @foreach($chartLabels as $label)
                        <span>{{ $label }}</span>
                    @endforeach
                </div>
            </div>

            <!-- RINGKASAN PANEN (lebih rapi) -->
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

    <!-- BOTTOM NAVIGATION -->
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
</body>

</html>