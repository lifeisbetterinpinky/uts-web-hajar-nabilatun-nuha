<?php
session_start();
include 'config/koneksi.php'; // Menghubungkan ke database

// Memanggil file autoload bawaan Composer agar PHPMailer aktif
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error_message = "";
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $nim   = trim($_POST['nim']);
    $nama  = trim($_POST['nama']);
    $prodi = trim($_POST['prodi']);
    $email = trim($_POST['email']);

    if (empty($nim) || empty($nama) || empty($prodi) || empty($email)) {
        $error_message = "Semua kolom wajib diisi!";
    } else {
        // Cek apakah NIM sudah terdaftar di tabel mahasiswa
        $cek_nim = mysqli_query($koneksi, "SELECT * FROM mahasiswa WHERE nim = '$nim'");
        if (mysqli_num_rows($cek_nim) > 0) {
            $error_message = "NIM tersebut sudah terdaftar!";
        } else {
            // 1. GENERATE PASSWORD ACAK (8 Karakter)
            $password_acak = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8);
            
            // Enkripsi password sebelum disimpan ke database
            $password_hashed = password_hash($password_acak, PASSWORD_BCRYPT);

            // 2. SIMPAN DATA KE DATABASE (Status default: nonaktif/0 sebelum verifikasi jika ada, atau sesuaikan sistemmu)
            $query = "INSERT INTO mahasiswa (nim, nama, prodi, email, password) VALUES ('$nim', '$nama', '$prodi', '$email', '$password_hashed')";
            
            if (mysqli_query($koneksi, $query)) {
                
                // 3. PROSES KIRIM EMAIL MENGGUNAKAN PHPMAILER
                $mail = new PHPMailer(true);

                try {
                    // Konfigurasi Server SMTP Gmail
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'peminjamanalatedp@gmail.com'; // Email Anda
                    $mail->Password   = 'vuyw sdrp pzve yzoq';        // App Password Gmail
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    // Penerima & Pengirim
                    $mail->setFrom('peminjamanalatedp@gmail.com', 'Sistem Monitoring Lab');
                    $mail->addAddress($email, $nama); // Kirim ke email mahasiswa yang didaftarkan

                    // Konten Email
                    $mail->isHTML(true);
                    $mail->Subject = 'Akun Mahasiswa Baru - Monitoring Lab';
                    $mail->Body    = "
                        <h3>Registrasi Berhasil!</h3>
                        <p>Halo <b>$nama</b>,</p>
                        <p>Akun Anda telah berhasil dibuat di Sistem Monitoring Alat Laboratorium.</p>
                        <p>Berikut adalah detail akun login Anda:</p>
                        <table border='0' cellpadding='5'>
                            <tr><td><b>Username (NIM)</b></td><td>: $nim</td></tr>
                            <tr><td><b>Password Sementara</b></td><td>: <span style='background:#f4f4f4; padding:2px 6px; font-family:monospace; font-weight:bold;'>$password_acak</span></td></tr>
                        </table>
                        <br>
                        <p><i>Silakan login ke sistem menggunakan NIM dan password sementara di atas, kemudian segera ubah password Anda demi keamanan.</i></p>
                        <br>
                        <p>Salam hangat,<br><b>Tim Administrator Lab</b></p>
                    ";

                    $mail->send();
                    $success_message = "Registrasi Berhasil! Password sementara telah dikirim ke email Anda.";
                } catch (Exception $e) {
                    $error_message = "Data tersimpan, tetapi GAGAL mengirim email. Error: {$mail->ErrorInfo}";
                }

            } else {
                $error_message = "Gagal menyimpan data ke database!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Monitoring Alat Laboratorium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="asset/style.css">

    <style>
        /* Mengatur sedikit penyesuaian khusus form registrasi */
        .auth-container {
            height: auto;
            min-height: 100vh;
        }
        .right-side {
            padding: 40px 60px;
            overflow-y: auto;
        }
        /* Style bantuan untuk membungkus pesan dari database PHP */
        .alert-msg {
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
        }
        .alert-danger { background-color: #FEECEB; color: #D32F2F; border: 1px solid #FCD4D2; }
        .alert-success { background-color: #E3F9E5; color: #1F7834; border: 1px solid #C1F0C5; }
    </style>
</head>
<body>

    <div class="auth-container">
        
        <div class="left-side">
            <div class="brand-logo"><i class="fas fa-microscope"></i> SISTEM MONITORING LAB</div>
            <div class="main-title-box">
                <h1>Daftar Akun<br>Mahasiswa Baru.</h1>
                <div class="line"></div>
            </div>
            <div></div>
            <div class="circle-decoration"></div>
            <div class="center-arrow-badge">
                <div class="arrow-inner" style="transform: rotate(180deg);"><i class="fas fa-arrow-right"></i></div>
            </div>
        </div>

        <div class="right-side">
            <div class="form-box">
                <h2>Registrasi</h2>

                <?php if (!empty($error_message)): ?>
                    <div class="alert-msg alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?></div>
                <?php endif; ?>

                <?php if (!empty($success_message)): ?>
                    <div class="alert-msg alert-success"><i class="fas fa-check-circle"></i> <?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <form action="" method="POST">
                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> Nomor Induk Mahasiswa (NIM)</label>
                        <input type="text" name="nim" class="form-control" placeholder="Masukkan NIM Anda" autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-graduation-cap"></i> Program Studi</label>
                        <input type="text" name="prodi" class="form-control" placeholder="Contoh: Teknik Informatika" autocomplete="off" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email Aktif</label>
                        <input type="email" name="email" class="form-control" placeholder="Contoh: mhs@gmail.com" autocomplete="off" required>
                    </div>

                    <button type="submit" name="register" class="btn-submit">
                        DAFTAR SEKARANG
                    </button>
                </form>

                <div class="divider-container">sudah memiliki akun mahasiswa?</div>
                <a href="login.php" class="btn-secondary">KEMBALI KE LOGIN</a>
            </div>
        </div>

    </div>

</body>
</html>