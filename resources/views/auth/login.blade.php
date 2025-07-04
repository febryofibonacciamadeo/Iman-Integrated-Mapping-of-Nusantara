<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In</title>
    <link rel="stylesheet" href="/auth-style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
    <body>
        <div class="container">
            <div class="signin">
                <h2>Daftar Akun Baru</h2>
                    <form id="loginForm">
                    <input type="email" id="email" name="email" placeholder="Email" required />
                    <input type="password" id="password" name="password" placeholder="Password" required />
                    <div class="link">Lupa kata sandi anda?</div>
                    <button type="submit">SIGN IN</button>
                </form>
            </div>
            <div class="signup">
                <h2>Halo, Teman!</h2>
                <p>Daftarkan diri anda dan mulai gunakan layanan kami segera</p>
                <button onclick="window.location.href='/register'">SIGN UP</button>
            </div>
        </div>

        <script>
            $('#loginForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: '/login',
                    type: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify({
                        email: $('#email').val(),
                        password: $('#password').val()
                    }),
                    success: function(response) {
                        alert('Login sukses!');
                        localStorage.setItem('token', response.token);
                        window.location.href = '/';
                    },
                    error: function(xhr) {
                        alert('Login gagal: ' + xhr.responseText);
                    }
                });
            });
        </script>
    </body>
</html>