<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RestoQ</title>
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>RestoQ</h1>
            <p>Silahkan login untuk melanjutkan</p>
        </div>
        
        <?php
        // Menampilkan pesan error jika ada
        if (isset($_GET['error'])) {
            echo '<div class="error-message">';
            if ($_GET['error'] == 'invalid') {
                echo 'Username atau password salah!';
            } elseif ($_GET['error'] == 'empty') {
                echo 'Harap isi semua field!';
            }
            echo '</div>';
        }
        ?>
        
        <form action="auth/login.php" method="POST">
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>