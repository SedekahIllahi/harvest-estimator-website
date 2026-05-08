<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiKAPAN – Ganti PIN</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f0f2ef;
            padding: 24px 16px;
            display: flex;
            justify-content: center;
        }
        .container {
            width: 390px;
            background: white;
            border-radius: 28px;
            padding: 28px 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        h1 {
            font-size: 24px;
            font-weight: 800;
            color: #1a2e1a;
            margin-bottom: 24px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-size: 13px;
            font-weight: 600;
            color: #6b8a6b;
            display: block;
            margin-bottom: 8px;
        }
        input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e0e6de;
            border-radius: 14px;
            font-family: inherit;
            font-size: 14px;
        }
        input:focus {
            border-color: #2d4a2d;
        }
        button {
            background: #2d4a2d;
            color: white;
            border: none;
            border-radius: 30px;
            padding: 14px;
            font-weight: 700;
            width: 100%;
            cursor: pointer;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 16px;
            color: #6b8a6b;
            text-decoration: none;
            font-size: 13px;
        }
        .error {
            color: #c0392b;
            font-size: 11px;
            margin-top: 4px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Ganti PIN</h1>
    <form action="{{ route('farmer.profile.pin') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>PIN Saat Ini</label>
            <input type="password" name="current_pin" required>
            @error('current_pin') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>PIN Baru</label>
            <input type="password" name="new_pin" required minlength="4">
            @error('new_pin') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label>Konfirmasi PIN Baru</label>
            <input type="password" name="new_pin_confirmation" required>
        </div>
        <button type="submit">Ganti PIN</button>
    </form>
    <a href="{{ route('farmer.profile') }}" class="back-link">← Kembali ke Profil</a>
</div>
</body>
</html>