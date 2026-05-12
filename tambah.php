<?php 
include 'config/koneksi.php'; 
session_start();

// Cek login agar aman
if ($_SESSION['status'] != "login") {
    header("location:login.php");
}

if (isset($_POST['simpan'])) {
    $nama  = $_POST['nama_alat'];
    $merk  = $_POST['merk'];
    $status = $_POST['status'];

    $query = "INSERT INTO alat_lab (nama_alat, merk, status) VALUES ('$nama', '$merk', '$status')";
    if (mysqli_query($koneksi, $query)) {
        header("location:index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Alat - Monitoring Alat Laboratorium</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <nav class="navbar">
        <h2>MONITORING ALAT LABORATORIUM</h2>
        <div class="menu">
            <a href="index.php">Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <div class="form-container">
            <h3>+ Input Alat Baru</h3>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Nama Alat</label>
                    <input type="text" name="nama_alat" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Merk</label>
                    <input type="text" name="merk" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="Baik">Baik</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>
                <button type="submit" name="simpan" class="btn-simpan">Simpan Data</button>
            </form>
        </div>
    </div>
</body>
</html>