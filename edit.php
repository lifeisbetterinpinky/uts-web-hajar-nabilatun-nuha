<?php 
include 'config/koneksi.php';
session_start();

// Cek apakah user sudah login (opsional, tergantung sistemmu)
// if ($_SESSION['status'] != "login") {
//     header("location:login.php");
// }

// Mengambil ID dari parameter URL yang dikirim index.php
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM alat_lab WHERE id='$id'");
$d = mysqli_fetch_array($query);

// Proses saat tombol SIMPAN diklik
if (isset($_POST['update'])) {
    $nama   = $_POST['nama_alat'];
    $merk   = $_POST['merk'];
    $status = $_POST['status'];

    $update = mysqli_query($koneksi, "UPDATE alat_lab SET nama_alat='$nama', merk='$merk', status='$status' WHERE id='$id'");
    
    if ($update) {
        // Kembali ke dashboard jika berhasil
        header("location:index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alat - Monitoring Alat Lab</title>
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

    <main class="container">
        <div class="page-header" style="border-left: 5px solid #96A78D; padding-left: 10px; margin-bottom: 20px;">
            <h3 style="color: #96A78D;">+ Edit Data Alat</h3>
        </div>
        
        <div class="form-container">
            <form action="" method="POST">
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #666;">Nama Alat</label>
                    <input type="text" name="nama_alat" class="form-input-text" 
                           value="<?php echo $d['nama_alat']; ?>" 
                           style="width: 100%; padding: 12px; border-radius: 20px; border: 1px solid #ddd;" required>
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #666;">Merk</label>
                    <input type="text" name="merk" class="form-input-text" 
                           value="<?php echo $d['merk']; ?>" 
                           style="width: 100%; padding: 12px; border-radius: 20px; border: 1px solid #ddd;" required>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; color: #666;">Status</label>
                    <select name="status" class="custom-select" 
                            style="width: 150px; padding: 10px; border-radius: 20px; background-color: #f0f0f0; border: none; font-weight: bold;">
                        <option value="Baik" <?php if($d['status'] == 'Baik') echo 'selected'; ?>>BAIK</option>
                        <option value="Rusak" <?php if($d['status'] == 'Rusak') echo 'selected'; ?>>RUSAK</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" name="update" class="btn-simpan-custom" 
                            style="padding: 10px 40px; border-radius: 20px; background-color: #999; color: white; border: none; cursor: pointer; font-weight: bold;">
                        SIMPAN
                    </button>
                    <a href="index.php" style="margin-left: 10px; text-decoration: none; color: #96A78D;">Batal</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>