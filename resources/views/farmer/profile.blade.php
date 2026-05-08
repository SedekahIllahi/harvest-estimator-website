<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>SiKAPAN – Profil</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --green-deep: #2d4a2d;
    --green-mid: #3a5c3a;
    --bg: #f0f2ef;
    --card-bg: #ffffff;
    --card-dark: #2d4a2d;
    --text-primary: #1a2e1a;
    --text-muted: #6b8a6b;
    --text-light: #a8bfa8;
    --divider: #e8ede8;
    --setting-bg: #ebebeb;
    --lahan-img: #f0e8ea;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--bg);
    min-height: 100vh;
    display: flex;
    justify-content: center;
  }

  .phone-shell {
    width: 390px;
    min-height: 844px;
    background: var(--bg);
    padding: 36px 16px 100px;
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .profile-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    animation: fadeUp 0.4s ease both;
  }

  .avatar-wrap {
    width: 80px;
    height: 80px;
    background: var(--green-deep);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 16px rgba(45,74,45,0.25);
  }
  .avatar-wrap svg { width: 38px; height: 38px; }

  .profile-name {
    font-size: 22px;
    font-weight: 800;
    color: var(--text-primary);
    letter-spacing: 0.2px;
  }

  .lahan-card {
    background: var(--green-deep);
    border-radius: 22px;
    padding: 22px 18px 20px;
    display: flex;
    flex-direction: column;
    gap: 14px;
    animation: fadeUp 0.4s 0.07s ease both;
  }

  .lahan-title {
    font-size: 17px;
    font-weight: 800;
    color: #fff;
    letter-spacing: 0.2px;
  }

  .lahan-map {
    width: 100%;
    height: 170px;
    background: var(--lahan-img);
    border-radius: 14px;
    overflow: hidden;
  }
  .lahan-map svg {
    width: 100%;
    height: 100%;
  }

  .lahan-info {
    background: #d6ddd6;
    border-radius: 12px;
    padding: 14px 16px;
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  .lahan-info-row {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-primary);
  }

  .setting-card {
    background: var(--setting-bg);
    border-radius: 22px;
    padding: 18px 16px 8px;
    animation: fadeUp 0.4s 0.14s ease both;
  }

  .setting-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 14px;
    padding-left: 4px;
  }

  .setting-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .setting-item {
    background: #fff;
    border-radius: 14px;
    padding: 15px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: background 0.15s, transform 0.12s;
  }
  .setting-item:hover { background: #f5f8f5; transform: translateX(2px); }
  .setting-item:active { transform: scale(0.98); }

  .setting-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .setting-icon svg { width: 20px; height: 20px; }

  .setting-label {
    flex: 1;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
  }

  .setting-value {
    font-size: 12px;
    font-weight: 500;
    color: var(--text-muted);
    margin-right: 4px;
  }

  .setting-chevron {
    color: var(--text-muted);
    display: flex;
    align-items: center;
  }
  .setting-chevron svg { width: 16px; height: 16px; }

  .setting-item.danger .setting-label { color: #c0392b; }
  .setting-item.danger .setting-icon svg path,
  .setting-item.danger .setting-icon svg circle,
  .setting-item.danger .setting-icon svg polyline { stroke: #c0392b; }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
  }

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
  .nav-item { display: flex; flex-direction: column; align-items: center; cursor: pointer; }
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

  <!-- PROFILE HEADER -->
  <div class="profile-header">
    <div class="avatar-wrap">
      <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
        <circle cx="12" cy="7" r="4"/>
      </svg>
    </div>
    <span class="profile-name">{{ $farmer->name }}</span>
  </div>

  <!-- LAHAN CARD -->
  <div class="lahan-card">
    <div class="lahan-title">Lahan Anda</div>
    <div class="lahan-map">
      <!-- SVG ilustrasi lahan (tetap statis, bisa dikustomisasi nanti) -->
      <svg viewBox="0 0 340 170" xmlns="http://www.w3.org/2000/svg">
        <rect width="340" height="170" fill="#f0e8ea"/>
        <rect x="0" y="60" width="340" height="110" fill="#d4e8c2"/>
        <rect x="0" y="60" width="340" height="8" fill="#b8d49a" opacity="0.7"/>
        <rect x="0" y="80" width="340" height="8" fill="#b8d49a" opacity="0.7"/>
        <rect x="0" y="100" width="340" height="8" fill="#b8d49a" opacity="0.7"/>
        <rect x="0" y="120" width="340" height="8" fill="#b8d49a" opacity="0.7"/>
        <rect x="0" y="140" width="340" height="8" fill="#b8d49a" opacity="0.7"/>
        <rect x="0" y="160" width="340" height="8" fill="#b8d49a" opacity="0.7"/>
        <line x1="85" y1="60" x2="85" y2="170" stroke="#9bc4e2" stroke-width="3" opacity="0.5"/>
        <line x1="170" y1="60" x2="170" y2="170" stroke="#9bc4e2" stroke-width="3" opacity="0.5"/>
        <line x1="255" y1="60" x2="255" y2="170" stroke="#9bc4e2" stroke-width="3" opacity="0.5"/>
        <rect x="150" y="0" width="40" height="170" fill="#e0d5b0" opacity="0.5"/>
        <circle cx="40" cy="45" r="18" fill="#6aad5e" opacity="0.8"/>
        <rect x="36" y="55" width="8" height="15" fill="#8b6914" opacity="0.7"/>
        <circle cx="300" cy="40" r="14" fill="#6aad5e" opacity="0.8"/>
        <rect x="296" y="50" width="8" height="14" fill="#8b6914" opacity="0.7"/>
        <rect x="155" y="20" width="30" height="22" fill="#e8c99a"/>
        <polygon points="148,22 170,8 192,22" fill="#c0392b" opacity="0.8"/>
        <rect x="163" y="30" width="8" height="12" fill="#7a5c3a" opacity="0.8"/>
        <circle cx="170" cy="95" r="10" fill="#2d4a2d" opacity="0.85"/>
        <circle cx="170" cy="95" r="5" fill="#fff" opacity="0.9"/>
        <line x1="170" y1="105" x2="170" y2="115" stroke="#2d4a2d" stroke-width="2.5" opacity="0.85"/>
      </svg>
    </div>
    <div class="lahan-info">
      <div class="lahan-info-row">Luas Lahan : {{ number_format($totalAreaSqm, 0, ',', '.') }} m²</div>
      <div class="lahan-info-row">Tanaman : {{ $crop }}</div>
    </div>
  </div>

  <!-- PENGATURAN CARD -->
  <div class="setting-card">
    <div class="setting-title">Pengaturan</div>
    <div class="setting-list">

      <!-- Bahasa (statis) -->
      <div class="setting-item">
        <div class="setting-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="#2d4a2d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="2" y1="12" x2="22" y2="12"/>
            <path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>
          </svg>
        </div>
        <span class="setting-label">Bahasa</span>
        <span class="setting-value">Bahasa Indonesia</span>
        <span class="setting-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></span>
      </div>

      <!-- Ubah Data Diri -->
      <a href="{{ route('farmer.profile.edit') }}" class="setting-item" style="text-decoration: none;">
        <div class="setting-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="#2d4a2d" stroke-width="2">
            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
          </svg>
        </div>
        <span class="setting-label">Ubah Data Diri</span>
        <span class="setting-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></span>
      </a>

      <!-- Ganti PIN -->
      <a href="{{ route('farmer.profile.pin.form') }}" class="setting-item" style="text-decoration: none;">
        <div class="setting-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="#2d4a2d" stroke-width="2">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0110 0v4"/>
          </svg>
        </div>
        <span class="setting-label">Ganti PIN</span>
        <span class="setting-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></span>
      </a>

      <!-- Chat Admin (WhatsApp) -->
      <a href="https://wa.me/6281234567890?text=Halo%20admin%20SiKAPAN" target="_blank" class="setting-item" style="text-decoration: none;">
        <div class="setting-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="#2d4a2d" stroke-width="2">
            <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
          </svg>
        </div>
        <span class="setting-label">Chat Admin</span>
        <span class="setting-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></span>
      </a>

      <!-- Log Out -->
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="setting-item danger" style="width: 100%; border: none; background: #fff; text-align: left; cursor: pointer;">
          <div class="setting-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#c0392b" stroke-width="2">
              <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
              <polyline points="16 17 21 12 16 7"/>
              <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
          </div>
          <span class="setting-label">Log Out</span>
          <span class="setting-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="#c0392b" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg></span>
        </button>
      </form>

    </div>
  </div>

</div>

<!-- BOTTOM NAV -->
<div class="bottom-nav">
  <a href="{{ route('farmer.dashboard') }}" class="nav-link">
    <div class="nav-item">
      <div class="nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="#6b8a6b" stroke-width="2">
          <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
      </div>
    </div>
  </a>
  <a href="{{ route('farmer.kalkulator') }}" class="nav-link">
    <div class="nav-item">
      <div class="nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="#6b8a6b" stroke-width="2">
          <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
        </svg>
      </div>
    </div>
  </a>
  <a href="{{ route('farmer.riwayat') }}" class="nav-link">
    <div class="nav-item">
      <div class="nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="#6b8a6b" stroke-width="2">
          <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
          <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
        </svg>
      </div>
    </div>
  </a>
  <a href="{{ route('farmer.profile') }}" class="nav-link">
    <div class="nav-item">
      <div class="nav-icon active">
        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
          <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
        </svg>
      </div>
    </div>
  </a>
</div>

@if(session('success'))
  <div style="position: fixed; bottom: 80px; left: 50%; transform: translateX(-50%); background: #2d4a2d; color: white; padding: 10px 20px; border-radius: 30px; font-size: 12px; z-index: 200;">
    {{ session('success') }}
  </div>
@endif

</body>
</html>