<?php 
include 'config/koneksi.php';
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM alat_lab WHERE id='$id'");
$data = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Data Alat - Lab</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="page-title">
            <div class="title-bar"></div>
            <h3>Edit Inventaris Alat</h3>
        </div>

        <form action="edit_aksi.php" method="post" class="login-card" style="max-width: 600px; margin: auto;">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            
            <div class="form-group">
                <label>Nama Alat</label>
                <input type="text" name="nama_alat" class="form-input-text" value="<?php echo $data['nama_alat']; ?>" required>
            </div>

            <div class="form-group">
                <label>Merk/Brand</label>
                <input type="text" name="merk" class="form-input-text" value="<?php echo $data['merk']; ?>" required>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Jumlah Baik</label>
                    <input type="number" name="jumlah_baik" class="form-input-text" value="<?php echo $data['jumlah_baik']; ?>" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Jumlah Rusak</label>
                    <input type="number" name="jumlah_rusak" class="form-input-text" value="<?php echo $data['jumlah_rusak']; ?>" required>
                </div>
            </div>

            <button type="submit" class="btn-simpan-custom">Update Data</button>
            <a href="index.php" style="display: block; margin-top: 15px; color: #666; text-decoration: none;">Batal</a>
        </form>
    </div>
</body>
</html>