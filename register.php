<?php 
include 'config/koneksi.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $pertanyaan = $_POST['pertanyaan']; 
    $jawaban    = $_POST['jawaban'];    
    
    $input = mysqli_query($koneksi, "INSERT INTO users (username, password, pertanyaan, jawaban) VALUES ('$username', '$password', '$pertanyaan', '$jawaban')");
    
    if ($input) {
        
        header("location:login.php?pesan=registrasi_berhasil");
    } else {
        echo "Gagal Registrasi: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Create Your Account</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-body">
    <div class="login-card">
        <h1>create account</h1>
        <p style="margin-bottom: 20px; color: #666;">Silakan buat akun untuk mengakses sistem</p>
        
        <form action="" method="POST">
            <div class="form-group" style="text-align: left; margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Username</label>
                <input type="text" name="username" class="login-input" placeholder="Choose a username" required>
            </div>
            <div class="form-group" style="text-align: left; margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Password</label>
                <input type="password" name="password" class="login-input" placeholder="Create a password" required>
            </div>
            <div class="form-group" style="text-align: left; margin-bottom: 15px;">
    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Pertanyaan Keamanan</label>
    <select name="pertanyaan" class="login-input" style="width: 100%; padding: 10px; border-radius: 20px; border: 1px solid #ddd;" required>
        <option value="Siapa nama ibu kandungmu?">Siapa nama ibu kandungmu?</option>
        <option value="Apa nama hewan peliharaanmu?">Apa nama hewan peliharaanmu?</option>
        <option value="Apa nama sekolah SD kamu?">Apa nama sekolah SD kamu?</option>
    </select>
</div>
<div class="form-group" style="text-align: left; margin-bottom: 20px;">
    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Jawaban</label>
    <input type="text" name="jawaban" class="login-input" placeholder="Jawaban kamu" required>
</div>
            <button type="submit" name="register" class="btn-masuk">Register Now</button>
        </form>

        <div class="login-footer" style="margin-top: 20px;">
            <p style="font-size: 0.9rem;">Already have an account? <a href="login.php" class="link-kecil">Login here</a></p>
        </div>
    </div>
</body>
</html>
