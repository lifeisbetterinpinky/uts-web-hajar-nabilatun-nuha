<?php 
include 'config/koneksi.php';
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM alat_lab WHERE id='$id'");
$data = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alat - MONITORING ALAT LABORATORIUM</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <h2><i class="fas fa-microscope"></i> MONITORING ALAT LABORATORIUM</h2>
        <div class="menu">
            <a href="index.php">
                <i class="fas fa-house"></i> Dashboard
            </a>
            <a href="tambah.php" class="btn-tambah">
                <i class="fas fa-plus-circle"></i> Tambah Alat Baru
            </a>
            <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">
                <i class="fas fa-door-open"></i> Logout
            </a>
        </div>
    </nav>

    <div class="content-area">
        <div class="page-title">
            <div class="title-bar"></div>
            <h2>Edit Inventaris Alat</h2>
        </div>

        <div class="form-container">
            <form action="edit_aksi.php" method="post">
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Alat</label>
                        <input type="text" name="nama_alat" class="form-input-text" value="<?php echo $data['nama_alat']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Merk/Brand</label>
                        <input type="text" name="merk" class="form-input-text" value="<?php echo $data['merk']; ?>" required>
                    </div>
                </div>

                <div style="display: flex; gap: 100px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label class="form-label">Total Alat di Lab</label>
                        <input type="number" name="jumlah_total" class="form-input-text" placeholder="Masukkan total stok manual" value="<?php echo isset($data['jumlah_total']) ? $data['jumlah_total'] : '0'; ?>" required>
                    </div>
                    <div class="form-group" style="flex:;">
                        <label class="form-label">Jumlah Kondisi Baik</label>
                        <input type="number" name="jumlah_baik" class="form-input-text" value="<?php echo $data['jumlah_baik']; ?>" required>
                    </div>
                    <div class="form-group" style="flex:;">
                        <label class="form-label">Jumlah Kondisi Rusak</label>
                        <input type="number" name="jumlah_rusak" class="form-input-text" value="<?php echo $data['jumlah_rusak']; ?>" required>
                    </div>
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" class="btn-simpan-custom">UPDATE DATA</button>
                    <a href="index.php" style="margin-left: 15px; color: #96A78D; text-decoration: none; font-weight: 600;">BATAL</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>>