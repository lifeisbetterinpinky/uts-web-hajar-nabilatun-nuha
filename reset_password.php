<?php 
include 'config/koneksi.php';
$user_target = $_GET['user']; // Mengambil username dari URL

if (isset($_POST['reset'])) {
    $password_baru = $_POST['password_baru'];
    
    // Perintah SQL untuk mengganti password
    $update = mysqli_query($koneksi, "UPDATE users SET password='$password_baru' WHERE username='$user_target'");
    
    if ($update) {
        echo "<script>alert('Password berhasil diperbarui! Silakan login.'); window.location='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-body">
    <div class="login-card">
        <h1>new password</h1>
        <p>Memperbarui password untuk user: <b><?php echo $user_target; ?></b></p>
        
        <form action="" method="POST">
            <div class="form-group" style="text-align: left; margin-top: 20px;">
                <input type="password" name="password_baru" class="login-input" placeholder="Masukkan Password Baru" required>
            </div>
            <button type="submit" name="reset" class="btn-masuk">Simpan Password</button>
        </form>
    </div>
</body>
</html>