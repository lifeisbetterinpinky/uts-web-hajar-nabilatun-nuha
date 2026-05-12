<?php 
include 'config/koneksi.php';
session_start(); // WAJIB ADA di baris paling atas agar session jalan

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Menggunakan tabel 'users' sesuai database kamu
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['status'] = "login"; // Ini adalah 'kunci' untuk masuk ke tambah.php
        header("location:index.php"); 
    } else {
        // Jika gagal, kembali ke login dengan pesan eror
        header("location:login.php?pesan=gagal");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login to your account</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body class="login-body">
    <div class="login-card">
        <h1>login to your account</h1>
        
        <?php if(isset($_GET['pesan']) && $_GET['pesan'] == "gagal"): ?>
            <p style="color: #721c24; background: #f8d7da; padding: 10px; border-radius: 10px; font-size: 0.8rem;">
                Username atau Password salah!
            </p>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group" style="text-align: left; margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Username</label>
                <input type="text" name="username" class="login-input" placeholder="Enter your username" required>
            </div>
            <div class="form-group" style="text-align: left; margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Password</label>
                <input type="password" name="password" class="login-input" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="login" class="btn-masuk">Login</button>
        </form>

        <div class="login-footer" style="margin-top: 20px;">
            <a href="#" class="link-kecil">lupa password?</a>
            <p style="font-size: 0.9rem; margin-top: 10px;">Don't have an account? <a href="register.php" class="link-kecil">Register</a></p>
        </div>
    </div>
</body>
</html>