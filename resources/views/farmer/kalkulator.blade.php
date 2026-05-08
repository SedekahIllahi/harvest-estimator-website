<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SiPanen – Kalkulator & Lapor Panen</title>
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
            padding-bottom: 100px;
            /* Space for navbar */
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.02), 0 8px 20px rgba(0, 0, 0, 0.05);
            overflow-x: hidden;
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
            font-size: 20px;
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

        /* ALERT */
        .alert {
            background: #eaf6ef;
            color: #2d4a2d;
            padding: 14px 16px;
            border-radius: 16px;
            font-size: 13px;
            font-weight: 700;
            border: 1px solid #cce4d4;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* CARD STYLING (Matched to Dashboard) */
        .form-card {
            background: white;
            border-radius: 28px;
            padding: 24px 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.02);
            border: 1px solid #eef2ec;
            animation: fadeUp 0.4s ease both;
        }

        .result-card {
            background: white;
            border-radius: 28px;
            padding: 24px 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.02);
            border: 1px solid #eef2ec;
            animation: fadeUp 0.4s ease both;
            animation-delay: 0.1s;
            min-height: 200px;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-title {
            font-size: 18px;
            font-weight: 800;
            color: #1e2e1e;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eef2ec;
        }

        /* FORM ELEMENTS */
        .field {
            margin-bottom: 18px;
        }

        .field-label {
            font-size: 12px;
            font-weight: 700;
            color: #6b8a6b;
            margin-bottom: 8px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .field input,
        .field select {
            width: 100%;
            background: #f8faf8;
            border: 1.5px solid #e5ebe3;
            border-radius: 16px;
            padding: 14px 16px;
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            color: #1a2e1a;
            outline: none;
            appearance: none;
            -webkit-appearance: none;
            transition: all 0.2s;
        }

        .field input[type="date"] {
            padding-right: 15px;
        }

        .field input::placeholder {
            color: #a8bfa8;
            font-weight: 500;
        }

        .field input:focus,
        .field select:focus {
            border-color: #3e9c5e;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(62, 156, 94, 0.1);
        }

        .select-wrap {
            position: relative;
        }

        .select-arrow {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #6b8a6b;
        }

        .select-arrow svg {
            width: 18px;
            height: 18px;
        }

        /* BUTTONS */
        .btn-kalkulasi {
            display: block;
            width: 100%;
            margin: 10px auto 0;
            background: #f0f4ef;
            color: #2d4a2d;
            border: 1.5px solid #dce8db;
            border-radius: 100px;
            padding: 16px 20px;
            font-size: 15px;
            font-weight: 800;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-kalkulasi:hover {
            background: #eaf1e9;
            transform: translateY(-1px);
        }

        .btn-submit {
            display: none;
            width: 100%;
            margin: 20px auto 0;
            background: #1e3a1e;
            color: #ffffff;
            border: none;
            border-radius: 100px;
            padding: 16px 20px;
            font-size: 15px;
            font-weight: 800;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 6px 16px rgba(30, 58, 30, 0.2);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(30, 58, 30, 0.3);
        }

        /* RESULT AREA */
        .result-content {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .result-item {
            background: #fafcfa;
            border: 1px solid #eef2ec;
            border-radius: 16px;
            padding: 14px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            opacity: 0;
            transform: translateX(-10px);
            transition: opacity 0.35s, transform 0.35s;
        }

        .result-item.show {
            opacity: 1;
            transform: translateX(0);
        }

        .result-item-label {
            font-size: 11px;
            font-weight: 700;
            color: #6b8a6b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .result-item-val {
            font-size: 15px;
            font-weight: 800;
            color: #1a2e1a;
        }

        .result-empty {
            height: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: #a8bfa8;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
        }

        .result-empty svg {
            width: 32px;
            height: 32px;
            stroke: #dce8db;
        }

        /* BOTTOM NAV (Exact Copy from Dashboard) */
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

        <div class="topbar">
            <span class="topbar-logo">Kalkulator</span>
            <div class="topbar-bell">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                </svg>
            </div>
        </div>

        <div class="scroll-area">

            @if(session('success'))
            <div class="alert">
                <i class="fas fa-check-circle" style="color: #3e9c5e; font-size: 16px;"></i>
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('farmer.ubinans.store') }}" method="POST" id="panenForm">
                @csrf

                <div class="form-card">
                    <div class="card-title">Data Ubinan</div>

                        <div class="field">
                            <label class="field-label">Lahan yang dipanen</label>
                            <div class="select-wrap">
                                <select name="land_id" id="lahanSelect" required>
                                    <option value="" disabled selected>Pilih sawah Anda...</option>
                                    @foreach(auth()->user()->lands ?? [] as $land)
                                    <option value="{{ $land->id }}" data-luas="{{ $land->area_size }}">
                                        {{ $land->nickname }} ({{ $land->area_size }} m²)
                                    </option>
                                    @endforeach
                                </select>
                                <span class="select-arrow">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label">Komoditas</label>
                        <div class="select-wrap">
                            <select name="crop_name" id="jenisSelect" required>
                                <option value="" data-price="0" disabled selected>Pilih Komoditas...</option>
                                @foreach($latestPrices ?? [] as $price)
                                <option value="{{ $price->crop_name }}" data-price="{{ $price->price_per_kg }}">
                                    {{ $price->crop_name }}
                                </option>
                                @endforeach
                            </select>
                            <span class="select-arrow">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label">Input berat sample ubinan (kg)</label>
                        <input type="number" name="sample_weight_kg" id="beratInput" placeholder="Contoh: 2.5" min="0"
                            step="0.1" required>
                    </div>

                    <div class="field">
                        <label class="field-label">Rencana Tanggal Panen</label>
                        <input type="date" name="projected_harvest_date" id="tanggalInput" required>
                    </div>

                    <input type="hidden" name="estimated_yield_kg" id="hiddenYield">

                    <button type="button" class="btn-kalkulasi" id="btnKalkulasi" onclick="kalkulasi()">Cek Estimasi
                        Hasil</button>
                </div>

                <div class="result-card" id="hasilCard">
                    <div class="card-title">Hasil Kalkulasi</div>

                    <div id="hasilEmpty" class="result-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        Lengkapi data form di atas<br>untuk melihat estimasi
                    </div>

                    <div id="hasilContent" class="result-content" style="display:none;"></div>

                    <button type="submit" class="btn-submit" id="btnSubmit">Kirim Laporan ke Sistem</button>
                </div>
            </form>

        </div>
    </div>

    <div class="bottom-nav">
        <a href="{{ route('farmer.dashboard') }}">
            <div class="nav-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
            </div>
        </a>
        <a href="{{ route('farmer.kalkulator') }}">
            <div class="nav-icon active">
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
        function fmt(n) {
            return new Intl.NumberFormat('id-ID').format(n);
        }

        function kalkulasi() {
            // 1. Grab the element itself so we can read data-attributes
            const lahanSelectElement = document.getElementById('lahanSelect');
            const lahanValue = lahanSelectElement.value; // Kept for your validation

            const beratInput = document.getElementById('beratInput').value;
            const select = document.getElementById('jenisSelect');
            const tanggalInput = document.getElementById('tanggalInput').value;

            const berat = parseFloat(beratInput);
            const jenis = select.value;
            const hargaPerKg = parseFloat(select.options[select.selectedIndex]?.getAttribute('data-price')) || 0;

            // 2. Extract the actual field size in m²
            const luasLahan = parseFloat(lahanSelectElement.options[lahanSelectElement.selectedIndex]?.getAttribute('data-luas')) || 0;

            if (!lahanValue || isNaN(berat) || berat <= 0 || !jenis || !tanggalInput) {
                const card = document.querySelector('.form-card');
                card.style.animation = 'none';
                card.offsetHeight; /* trigger reflow */
                card.style.animation = 'fadeUp 0.4s ease both';
                alert('Harap lengkapi semua data (Lahan, Komoditas, Berat, dan Tanggal) terlebih dahulu!');
                return;
            }

            // --- 3. RUMUS UBINAN AKURAT (m²) ---
            const multiplier = luasLahan / 6.25;
            const totalKg = berat * multiplier;
            const totalTon = totalKg / 1000;

            // Set hidden input for backend
            document.getElementById('hiddenYield').value = totalKg;

            const estimasiMin = (totalTon * 0.85).toFixed(2);
            const estimasiMax = (totalTon * 1.15).toFixed(2);
            const nilaiMin = Math.round(totalKg * 0.85 * hargaPerKg);
            const nilaiMax = Math.round(totalKg * 1.15 * hargaPerKg);

            const rows = [{
                    label: 'Komoditas',
                    val: jenis
                },
                {
                    label: 'Total Panen',
                    val: estimasiMin + ' - ' + estimasiMax + ' Ton'
                }, // Label updated
                {
                    label: 'Estimasi Nilai',
                    val: 'Rp ' + fmt(nilaiMin) + ' - ' + fmt(nilaiMax)
                },
            ];

            const content = document.getElementById('hasilContent');
            const empty = document.getElementById('hasilEmpty');
            const submitBtn = document.getElementById('btnSubmit');

            content.innerHTML = '';
            empty.style.display = 'none';
            content.style.display = 'flex';

            rows.forEach((r, i) => {
                const div = document.createElement('div');
                div.className = 'result-item';
                div.innerHTML = `<span class="result-item-label">${r.label}</span><span class="result-item-val">${r.val}</span>`;
                content.appendChild(div);
                setTimeout(() => div.classList.add('show'), i * 80);
            });

            // Show the final submit button!
            setTimeout(() => {
                submitBtn.style.display = 'block';
                submitBtn.style.animation = 'fadeUp 0.4s ease both';
            }, 300);
        }
    </script>
</body>

</html>