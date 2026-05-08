<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px;">
        <h2>Register</h2>
        <form method="POST" action="/register">
            @csrf
            <div style="margin-bottom: 15px;">
                <label>Name:</label>
                <input type="text" name="name" required style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Email:</label>
                <input type="email" name="email" required style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Password:</label>
                <input type="password" name="password" required style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Confirm Password:</label>
                <input type="password" name="password_confirmation" required style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>
            <button type="submit" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; cursor: pointer;">Register</button>
        </form>
    </div>
</body>
</html>