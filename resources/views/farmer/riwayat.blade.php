<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title>SiKAPAN – Riwayat Panen</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --green-deep: #2d4a2d;
    --bg: #f0f2ef;
    --card-bg: #ffffff;
    --text-primary: #1a2e1a;
    --text-muted: #6b8a6b;
    --text-light: #a8bfa8;
    --tag-green: #e6f0e6;
    --tag-green-text: #2d6a2d;
    --tag-orange: #fff1e6;
    --tag-orange-text: #b85a00;
    --tag-red: #ffe6e6;
    --tag-red-text: #b33;
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
    padding-bottom: 90px;
    position: relative;
  }

  .header {
    padding: 28px 20px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .header-title {
    font-size: 22px;
    font-weight: 800;
    color: var(--text-primary);
  }
  .header-filter {
    width: 36px; height: 36px;
    background: var(--card-bg);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 6px rgba(0,0,0,0.07);
    cursor: pointer;
  }
  .header-filter svg { width: 18px; height: 18px; }

  .search-wrap {
    padding: 0 16px 14px;
  }
  .search-box {
    background: #e0e6de;
    border-radius: 12px;
    padding: 13px 18px;
    display: flex;
    align-items: center;
    gap: 10px;
    height: 60px;
  }
  .search-box svg { width: 18px; height: 18px; color: var(--text-muted); }
  .search-box input {
    border: none; background: transparent;
    font-size: 14px; font-family: inherit;
    font-weight: 500; color: var(--text-primary);
    outline: none; width: 100%;
  }
  .search-box input::placeholder { color: var(--text-muted); }

  .list-wrap {
    padding: 0 16px;
    background: var(--card-bg);
    border-radius: 20px;
    margin: 0 8px;
    overflow: hidden;
    box-shadow: 0 2px 14px rgba(45,74,45,0.07);
  }

  .harvest-item {
    padding: 16px 0;
    border-bottom: 1px solid #f0f4f0;
    display: flex;
    align-items: center;
    gap: 14px;
  }
  .harvest-item:last-child { border-bottom: none; }

  .harvest-icon {
    width: 46px; height: 46px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
  }
  .harvest-icon.green { background: var(--tag-green); }
  .harvest-icon.orange { background: var(--tag-orange); }
  .harvest-icon.blue { background: #e8ecff; }

  .harvest-info { flex: 1; min-width: 0; }
  .harvest-name {
    font-size: 14px; font-weight: 700;
    color: var(--text-primary);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }
  .harvest-date { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

  .harvest-right { text-align: right; flex-shrink: 0; }
  .harvest-amount {
    font-size: 15px; font-weight: 800; color: var(--text-primary);
  }
  .harvest-tag {
    display: inline-block;
    margin-top: 4px;
    font-size: 10px; font-weight: 700;
    border-radius: 100px;
    padding: 2px 8px;
    letter-spacing: 0.3px;
  }
  .harvest-tag.sukses { background: var(--tag-green); color: var(--tag-green-text); }
  .harvest-tag.proses { background: var(--tag-orange); color: var(--tag-orange-text); }
  .harvest-tag.gagal { background: var(--tag-red); color: var(--tag-red-text); }

  .section-label {
    padding: 16px 24px 8px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--text-muted);
  }

  .empty-state {
    padding: 48px 24px;
    text-align: center;
    color: var(--text-light);
    font-size: 14px;
    display: none;
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
  .nav-item { display: flex; flex-direction: column; align-items: center; gap: 3px; cursor: pointer; }
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

  <div class="header">
    <span class="header-title">Riwayat Panen</span>
    <div class="header-filter">
      <svg viewBox="0 0 24 24" fill="none" stroke="#2d4a2d" stroke-width="2.2">
        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
      </svg>
    </div>
  </div>

  <div class="search-wrap">
    <div class="search-box">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
      </svg>
      <input type="text" id="searchInput" placeholder="Cari riwayat panen..." oninput="filterList()">
    </div>
  </div>

  <!-- BULAN INI -->
  @if(count($currentMonthItems) > 0)
  <div class="section-label">Bulan Ini</div>
  <div class="list-wrap">
    @foreach($currentMonthItems as $item)
    <div class="harvest-item" data-name="{{ $item['name'] }}">
      <div class="harvest-icon {{ $loop->index % 2 == 0 ? 'green' : 'orange' }}">{{ $item['icon'] }}</div>
      <div class="harvest-info">
        <div class="harvest-name">{{ $item['name'] }}</div>
        <div class="harvest-date">{{ $item['date'] }} · {{ $item['location'] }}</div>
      </div>
      <div class="harvest-right">
        <div class="harvest-amount">{{ $item['amount_ton'] }} Ton</div>
        <span class="harvest-tag {{ $item['tag_class'] }}">{{ $item['status'] }}</span>
      </div>
    </div>
    @endforeach
  </div>
  @endif

  <!-- BULAN LALU -->
  @if(count($lastMonthItems) > 0)
  <div class="section-label">Bulan Lalu</div>
  <div class="list-wrap">
    @foreach($lastMonthItems as $item)
    <div class="harvest-item" data-name="{{ $item['name'] }}">
      <div class="harvest-icon {{ $loop->index % 2 == 0 ? 'green' : 'orange' }}">{{ $item['icon'] }}</div>
      <div class="harvest-info">
        <div class="harvest-name">{{ $item['name'] }}</div>
        <div class="harvest-date">{{ $item['date'] }} · {{ $item['location'] }}</div>
      </div>
      <div class="harvest-right">
        <div class="harvest-amount">{{ $item['amount_ton'] }} Ton</div>
        <span class="harvest-tag {{ $item['tag_class'] }}">{{ $item['status'] }}</span>
      </div>
    </div>
    @endforeach
  </div>
  @endif

  <!-- SEBELUMNYA -->
  @if(count($olderItems) > 0)
  <div class="section-label">Sebelumnya</div>
  <div class="list-wrap">
    @foreach($olderItems as $item)
    <div class="harvest-item" data-name="{{ $item['name'] }}">
      <div class="harvest-icon {{ $loop->index % 2 == 0 ? 'green' : 'orange' }}">{{ $item['icon'] }}</div>
      <div class="harvest-info">
        <div class="harvest-name">{{ $item['name'] }}</div>
        <div class="harvest-date">{{ $item['date'] }} · {{ $item['location'] }}</div>
      </div>
      <div class="harvest-right">
        <div class="harvest-amount">{{ $item['amount_ton'] }} Ton</div>
        <span class="harvest-tag {{ $item['tag_class'] }}">{{ $item['status'] }}</span>
      </div>
    </div>
    @endforeach
  </div>
  @endif

  <div class="empty-state" id="emptyState">Tidak ada riwayat yang ditemukan.</div>

</div>

<!-- BOTTOM NAV (sesuai route Laravel) -->
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
      <div class="nav-icon active">
        <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
          <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
          <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
        </svg>
      </div>
    </div>
  </a>
  <a href="{{ route('farmer.profile') }}" class="nav-link">
    <div class="nav-item">
      <div class="nav-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="#6b8a6b" stroke-width="2">
          <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
        </svg>
      </div>
    </div>
  </a>
</div>

<script>
  function filterList() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const items = document.querySelectorAll('.harvest-item');
    let shown = 0;
    items.forEach(item => {
      const name = item.dataset.name.toLowerCase();
      const visible = name.includes(q);
      item.style.display = visible ? 'flex' : 'none';
      if (visible) shown++;
    });
    document.getElementById('emptyState').style.display = shown === 0 ? 'block' : 'none';
  }
</script>

</body>
</html>