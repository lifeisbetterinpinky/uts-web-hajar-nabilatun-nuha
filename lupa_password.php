<?php 
include 'config/koneksi.php';

if (isset($_POST['cek'])) {
    $username = $_POST['username'];
    $jawaban  = $_POST['jawaban'];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND jawaban_keamanan='$jawaban'");
    
    if (mysqli_num_rows($query) > 0) {
        
        header("location:reset_password.php?user=$username");
    } else {
        echo "<script>alert('Data tidak cocok! Silakan cek kembali username dan jawaban Anda.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-body">
    <div class="login-card">
        <h1>verify account</h1>
        <p style="margin-bottom: 20px; color: #666;">Masukkan jawaban keamanan untuk reset password</p>
        
        <form action="" method="POST">
            <div class="form-group" style="text-align: left; margin-bottom: 15px;">
                <label>Username</label>
                <input type="text" name="username" class="login-input" placeholder="Username Anda" required>
            </div>
            <div class="form-group" style="text-align: left; margin-bottom: 20px;">
                <label>Jawaban Keamanan</label>
                <input type="text" name="jawaban" class="login-input" placeholder="Apa jawaban keamanan Anda?" required>
            </div>
            <button type="submit" name="cek" class="btn-masuk">Verifikasi</button>
        </form>

        <div style="margin-top: 20px;">
            <a href="login.php" class="link-kecil">Kembali ke Login</a>
        </div>
    </div>
</body>
</html>