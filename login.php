<?php 
session_start();
if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    header("location:index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring Alat Laboratorium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="asset/style.css">

    <style>
        /* Style bantuan untuk membungkus pesan error dari database PHP */
        .alert-msg {
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
        }
        .alert-danger { background-color: #FEECEB; color: #D32F2F; border: 1px solid #FCD4D2; }
        .alert-info { background-color: #EBF8FF; color: #2B6CB0; border: 1px solid #BEE3F8; }
    </style>
</head>
<body>

    <div class="auth-container">
        
        <div class="left-side">
            <div class="brand-logo"><i class="fas fa-microscope"></i> SISTEM MONITORING LAB</div>
            <div class="main-title-box">
                <h1>Monitoring Alat<br>Laboratorium.</h1>
                <div class="line"></div>
            </div>
            <div></div>
            <div class="circle-decoration"></div>
            <div class="center-arrow-badge">
                <div class="arrow-inner"><i class="fas fa-arrow-right"></i></div>
            </div>
        </div>

        <div class="right-side">
            <div class="form-box">
                <h2>Selamat Datang!</h2>

                <?php 
                if (isset($_GET['pesan'])) {
                    if ($_GET['pesan'] == "gagal") {
                        echo "<div class='alert-msg alert-danger'><i class='fas fa-exclamation-circle'></i> Login gagal! Username atau password salah.</div>";
                    } else if ($_GET['pesan'] == "logout") {
                        echo "<div class='alert-msg alert-info'><i class='fas fa-info-circle'></i> Anda telah berhasil logout.</div>";
                    } else if ($_GET['pesan'] == "belum_login") {
                        echo "<div class='alert-msg alert-danger'><i class='fas fa-lock'></i> Anda harus login untuk mengakses halaman admin.</div>";
                    }
                }
                ?>
                
                <form action="login_aksi.php" method="POST">
                    <div class="form-group">
                        <label>Username / NIM</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan Username atau NIM" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                    </div>
                    <button type="submit" class="btn-submit">Log in</button>
                </form>

                <div class="divider-container">belum punya akun mahasiswa?</div>
                <a href="register.php" class="btn-secondary">BUAT AKUN</a>
            </div>
        </div>

    </div>

</body>
</html>