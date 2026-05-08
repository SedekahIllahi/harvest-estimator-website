<h1>TESTING SANDBOX</h1>

<h3>Test Save Ubinan</h3>
<form action="/ubinans" method="POST">
    @csrf <input type="hidden" name="land_id" value="1"> 
    
    <label>Sample Weight (Kg):</label>
    <input type="number" step="0.01" name="sample_weight_kg" value="12.5"><br><br>
    
    <label>Weather Note:</label>
    <input type="text" name="weather_note" value="Cerah"><br><br>
    
    <button type="submit">TEST SUBMIT</button>
</form>

@if(session('success'))
    <p style="color: green;"><b>{{ session('success') }}</b></p>
@endif
@if(session('error'))
    <p style="color: red;"><b>{{ session('error') }}</b></p>
@endif

<!-- Only show the form if user is authenticated -->
@if(auth()->check())
    <p>Welcome back, {{ auth()->user()->name }}!</p>
@endif

<!-- Redirect to login if user is not authenticated -->
@if(!auth()->check())
    <p>Please login to access this page.</p>
    <a href="/login">Login</a> | <a href="/register">Register</a>
@endif

<!-- Show registration form if user is not authenticated -->
@if(!auth()->check())
    <div style="margin-top: 20px;">
        <h3>Or register to create an account:</h3>
        <a href="/register">Register Now</a>
    </div>
@endif

<hr><br>
<h3>[ADMIN] Test Pendaftaran Petani & Lahan</h3>

@if(session('success'))
    <p style="color: green;"><b>{{ session('success') }}</b></p>
@endif
@if($errors->any())
    <p style="color: red;"><b>{{ $errors->first() }}</b></p>
@endif

<form action="{{ route('admin.farmers.store') }}" method="POST">
    @csrf
    <label>Nama Petani:</label>
    <input type="text" name="name" value="Pak Tani Tester"><br><br>

    <label>Nomor HP:</label>
    <input type="text" name="phone" value="089999999999"><br><br>

    <label>PIN Rahasia:</label>
    <input type="text" name="pin" value="1234"><br><br>

    <label>Nama Sawah:</label>
    <input type="text" name="sawah_name" value="Sawah Pojok Desa"><br><br>

    <label>Luas (Hektar dari Peta):</label>
    <input type="number" step="0.0001" name="area_hectares" value="1.2500"><br><br>

    <label>Latitude:</label>
    <input type="text" name="lat" value="-7.251234"><br><br>

    <label>Longitude:</label>
    <input type="text" name="lng" value="110.123456"><br><br>

    <button type="submit">SIMPAN KE DATABASE</button>
</form>
