<?php 
include 'config/koneksi.php';
session_start();

if ($_SESSION['status'] != "login") {
    header("location:login.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Alat - MONITORING ALAT LABORATORIUM</title>
    <link rel="stylesheet" href="assets/style.css">
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
            <form action="tambah_aksi.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Alat</label>
                        <input type="text" name="nama_alat" class="form-input-text" placeholder="Ketik Nama Alat" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Merk</label>
                        <input type="text" name="merk" class="form-input-text" placeholder="Ketik Merk Alat" required>
                    </div>
                </div>

                <div style="display: flex; gap: 100px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label class="form-label">Total Alat di Lab</label>
                        <input type="number" name="jumlah_total" class="form-input-text" placeholder="Masukkan total stok manual" value="<?php echo isset($data['jumlah_total']) ? $data['jumlah_total'] : '0'; ?>" required>
                    </div>
                    <div class="form-group" style="flex: ;">
                        <label class="form-label">Jumlah Kondisi Baik</label>
                        <input type="number" name="jumlah_baik" class="form-input-text" value="0" required>
                    </div>
                    <div class="form-group" style="flex: ;">
                        <label class="form-label">Jumlah Kondisi Rusak</label>
                        <input type="number" name="jumlah_rusak" class="form-input-text" value="0" required>
                    </div>
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" name="simpan" class="btn-simpan-custom">SIMPAN DATA</button>
                    <a href="index.php" style="margin-left: 15px; color: #96A78D; text-decoration: none; font-weight: 600;">BATAL</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>