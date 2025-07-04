<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register</title>
    <link rel="stylesheet" href="/auth-style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
    <body>

    <div class="container">
        <div class="register">
            <h2>Daftar Akun Baru</h2>
            <form id="registerForm">
                <input type="text" id="name" name="name" placeholder="Username" required />
                <input type="email" id="email" name="email" placeholder="Email" required />
                <input type="password" id="password" name="password" placeholder="Password" required />
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" required />
                <button type="submit">SIGN UP</button>
            </form>
        </div>
        <div class="login-link">
            <h2>Sudah punya akun?</h2>
            <p>Silakan login untuk masuk ke dashboard</p>
            <button onclick="window.location.href='/login'">SIGN IN</button>
        </div>
    </div>

    <script>
        $('#registerForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
            url: '/register',
            type: 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({
                name: $('#name').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                password_confirmation: $('#password_confirmation').val()
            }),
            success: function(response) {
                alert('Registrasi sukses!');
                window.location.href = '/login';
            },
            error: function(xhr) {
                alert('Gagal registrasi: ' + xhr.responseText);
            }
            });
        });
    </script>
    
    </body>
</html>
