<?php
session_start();
session_destroy(); // Menghapus sesi login
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Berhasil</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="logout-container">
        <div class="logout-msg-card">
            <h2>Logout Berhasil!</h2>
            <p>Sesi Anda telah berakhir. Terima kasih telah menggunakan sistem ini.</p>
            
            <a href="login.php" class="link-login-kecil">
                &larr; Kembali ke halaman Login
            </a>
        </div>
    </div>
</body>
</html>