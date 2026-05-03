<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>SiKAPAN – Kalkulator Pertanian</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  /* (SELURUH STYLE TIDAK BERUBAH, TETAP SAMA DENGAN KODE YANG ANDA BERIKAN) */
  :root {
    --green-deep: #2d4a2d;
    --green-mid: #3a5c3a;
    --green-light: #4e7a4e;
    --bg: #f0f2ef;
    --card-bg: #ffffff;
    --text-primary: #1a2e1a;
    --text-muted: #6b8a6b;
    --text-light: #a8bfa8;
    --accent: #4e7a4e;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--bg);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    padding: 0;
  }

  .phone-shell {
    width: 390px;
    min-height: 844px;
    background: var(--bg);
    position: relative;
    overflow: hidden;
    padding: 24px 16px 100px;
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  /* ─── KALKULATOR CARD ─── */
  .card {
    background: var(--green-deep);
    border-radius: 24px;
    padding: 28px 24px 30px;
    animation: fadeUp 0.4s ease both;
  }
  .card.result {
    background: var(--green-mid);
    animation-delay: 0.1s;
    min-height: 220px;
  }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .card-title {
    font-size: 20px;
    font-weight: 800;
    color: #fff;
    text-align: center;
    margin-bottom: 26px;
    letter-spacing: 0.3px;
  }

  /* ─── FORM ELEMENTS ─── */
  .field { margin-bottom: 18px; }
  .field-label {
    font-size: 13px;
    font-weight: 600;
    color: rgba(255,255,255,0.85);
    margin-bottom: 8px;
    display: block;
  }
  .field input,
  .field select {
    width: 100%;
    background: #fff;
    border: none;
    border-radius: 100px;
    padding: 14px 20px;
    font-size: 15px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 500;
    color: var(--text-primary);
    outline: none;
    appearance: none;
    -webkit-appearance: none;
    transition: box-shadow 0.2s;
  }
  .field input::placeholder { color: #b0c8b0; }
  .field input:focus,
  .field select:focus {
    box-shadow: 0 0 0 3px rgba(255,255,255,0.35);
  }

  .select-wrap { position: relative; }
  .select-wrap select {
    cursor: pointer;
    padding-right: 44px;
    color: var(--text-primary);
  }
  .select-arrow {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: var(--green-deep);
  }
  .select-arrow svg { width: 22px; height: 22px; }

  /* ─── BUTTON ─── */
  .btn-kalkulasi {
    display: block;
    margin: 8px auto 0;
    background: #d8e0d8;
    color: var(--text-primary);
    border: none;
    border-radius: 100px;
    padding: 14px 48px;
    font-size: 15px;
    font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer;
    transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
    letter-spacing: 0.2px;
  }
  .btn-kalkulasi:hover {
    background: #c5d0c5;
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
  }
  .btn-kalkulasi:active { transform: scale(0.97); }
  .btn-kalkulasi:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  /* ─── RESULT AREA ─── */
  .result-content {
    display: flex;
    flex-direction: column;
    gap: 14px;
    padding-top: 4px;
  }
  .result-item {
    background: rgba(255,255,255,0.10);
    border-radius: 14px;
    padding: 14px 18px;
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
    font-size: 12px;
    font-weight: 600;
    color: rgba(255,255,255,0.65);
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .result-item-val {
    font-size: 17px;
    font-weight: 800;
    color: #fff;
  }
  .result-empty {
    height: 140px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.25);
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.3px;
  }

  /* ─── BOTTOM NAV ─── */
  .bottom-nav {
    position: fixed;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 390px;
    background: #fff;
    border-top: 1px solid #e8ede8;
    display: flex;
    align-items: center;
    justify-content: space-around;
    padding: 14px 24px 22px;
    z-index: 100;
  }
  .nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
    cursor: pointer;
  }
  .nav-icon {
    width: 42px; height: 42px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    background: #f0f2ef;
  }
  .nav-icon.active { background: var(--green-deep); }
  .nav-icon svg { width: 20px; height: 20px; }
</style>
</head>
<body>
<div class="phone-shell">

  <!-- KALKULATOR CARD -->
  <div class="card">
    <div class="card-title">Kalkulator Pertanian</div>

    <div class="field">
      <label class="field-label">Input berat sample (kg)</label>
      <input type="number" id="beratInput" placeholder="Contoh: 2.5" min="0" step="0.1">
    </div>

    <div class="field">
      <label class="field-label">Jenis Pertanian</label>
      <div class="select-wrap">
        <select id="jenisSelect">
          <option value="" disabled selected>Memuat data...</option>
        </select>
        <span class="select-arrow">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"/>
          </svg>
        </span>
      </div>
    </div>

    <button class="btn-kalkulasi" id="btnKalkulasi" onclick="kalkulasi()" disabled>Kalkulasi</button>
  </div>

  <!-- HASIL CARD -->
  <div class="card result" id="hasilCard">
    <div class="card-title">Hasil Kalkulasi Pertanian</div>
    <div id="hasilEmpty" class="result-empty">Masukkan data untuk melihat hasil</div>
    <div id="hasilContent" class="result-content" style="display:none;"></div>
  </div>

</div>

<!-- BOTTOM NAV (menggunakan route Laravel) -->
<div class="bottom-nav">
  <a href="{{ route('farmer.dashboard') }}" class="nav-link">
    <div class="nav-item">
      <div class="nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="#6b8a6b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
      </div>
    </div>
  </a>

  <a href="{{ route('farmer.kalkulator') }}" class="nav-link">
    <div class="nav-item">
      <div class="nav-icon active">
        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
        </svg>
      </div>
    </div>
  </a>

  <a href="{{ route('farmer.riwayat') }}" class="nav-link">
    <div class="nav-item">
      <div class="nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="#6b8a6b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
          <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
        </svg>
      </div>
    </div>
  </a>

  <a href="{{ route('farmer.profile') }}" class="nav-link">
    <div class="nav-item">
      <div class="nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="#6b8a6b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
        </svg>
      </div>
    </div>
  </a>
</div>

<script>
  // Objek untuk menyimpan data faktor dari database
  let factors = {};
  let isDataLoaded = false;

  // Fungsi untuk memuat data dari API /api/prices
  async function loadPrices() {
    try {
      const response = await fetch('/api/prices');
      const data = await response.json();

      // data: array of { slug, commodity, price_per_kg, conversion_factor }
      data.forEach(item => {
        factors[item.slug] = {
          nama: item.commodity,
          faktor: parseFloat(item.conversion_factor),
          harga: parseFloat(item.price_per_kg),
          unit: 'Ton'
        };
      });

      // Isi dropdown select dengan data yang tersedia
      const select = document.getElementById('jenisSelect');
      select.innerHTML = '<option value="" disabled selected>Pilih jenis tanaman</option>';
      for (const slug in factors) {
        const option = document.createElement('option');
        option.value = slug;
        option.textContent = factors[slug].nama;
        select.appendChild(option);
      }

      isDataLoaded = true;
      // Aktifkan tombol kalkulasi
      document.getElementById('btnKalkulasi').disabled = false;
    } catch (error) {
      console.error('Gagal memuat data harga:', error);
      const select = document.getElementById('jenisSelect');
      select.innerHTML = '<option value="" disabled selected>Gagal memuat data</option>';
      alert('Gagal memuat data komoditas. Silakan refresh halaman.');
    }
  }

  function fmt(n) { return new Intl.NumberFormat('id-ID').format(n); }

  function kalkulasi() {
    if (!isDataLoaded) {
      alert('Data komoditas belum siap, coba beberapa saat lagi.');
      return;
    }

    const berat = parseFloat(document.getElementById('beratInput').value);
    const jenis = document.getElementById('jenisSelect').value;

    if (isNaN(berat) || berat <= 0 || !jenis) {
      const card = document.querySelector('.card');
      card.style.animation = 'none';
      card.offsetHeight;
      card.style.animation = 'fadeUp 0.4s ease both';
      alert('Harap isi berat sample dan pilih jenis tanaman!');
      return;
    }

    const f = factors[jenis];
    if (!f) {
      alert('Data komoditas tidak ditemukan.');
      return;
    }

    const estimasiMin = (berat * f.faktor * 0.85).toFixed(1);
    const estimasiMax = (berat * f.faktor * 1.15).toFixed(1);
    const nilaiMin = Math.round(berat * f.faktor * 0.85 * f.harga);
    const nilaiMax = Math.round(berat * f.faktor * 1.15 * f.harga);

    const rows = [
      { label: 'Jenis Tanaman', val: f.nama },
      { label: 'Berat Sample', val: berat + ' kg' },
      { label: 'Estimasi Panen', val: estimasiMin + ' – ' + estimasiMax + ' Ton' },
      { label: 'Estimasi Nilai', val: 'Rp ' + fmt(nilaiMin) + ' – ' + fmt(nilaiMax) },
    ];

    const content = document.getElementById('hasilContent');
    const empty = document.getElementById('hasilEmpty');
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
  }

  // Muat data saat halaman selesai dimuat
  window.addEventListener('DOMContentLoaded', loadPrices);
</script>
</body>
</html>