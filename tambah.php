<?php 
include 'config/koneksi.php';
session_start();

if ($_SESSION['status'] != "login") {
    header("location:login.php");
}

if (isset($_POST['simpan'])) {
    $nama   = $_POST['nama_alat'];
    $merk   = $_POST['merk'];
    $status = $_POST['status'];

    
    $input = mysqli_query($koneksi, "INSERT INTO alat_lab (nama_alat, merk, status) VALUES ('$nama', '$merk', '$status')");
    
    if ($input) {
        header("location:index.php?pesan=tambah_berhasil");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Alat - MONITORING ALAT LABORATORIUM</title>
    <link rel="stylesheet" href="assets/style.css?v=1">
</head>
<body>
    <nav class="navbar">
        <h2>MONITORING ALAT LABORATORIUM</h2>
        <div class="menu">
            <a href="index.php">Dashboard</a>
            <a href="tambah.php" class="btn-tambah">Tambah Alat Baru</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="content-area">
        <div class="page-title">
            <div class="title-bar"></div>
            <h2>+ Input Alat Baru</h2>
        </div>

        <div class="form-container">
            <form action="" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Alat</label>
                        <input type="text" name="nama_alat" class="form-input-text" placeholder="Masukkan nama alat..." required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Merk</label>
                        <input type="text" name="merk" class="form-input-text" placeholder="Masukkan merk alat..." required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div class="select-wrapper">
                        <select name="status" class="custom-select" required>
                            <option value="" disabled selected>- Pilih Status -</option>
                            <option value="Baik">BAIK</option>
                            <option value="Rusak">RUSAK</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" name="simpan" class="btn-simpan-custom">SIMPAN</button>
                    <a href="index.php" style="margin-left: 15px; color: #96A78D; text-decoration: none; font-weight: 600;">BATAL</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>