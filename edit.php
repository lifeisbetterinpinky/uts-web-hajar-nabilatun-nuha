<?php 
include 'config/koneksi.php';
session_start();

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM alat_lab WHERE id='$id'");
$d = mysqli_fetch_array($query);

if (isset($_POST['update'])) {
    $nama   = $_POST['nama_alat'];
    $merk   = $_POST['merk'];
    $status = $_POST['status'];

    $update = mysqli_query($koneksi, "UPDATE alat_lab SET nama_alat='$nama', merk='$merk', status='$status' WHERE id='$id'");
    
    if ($update) {
        header("location:index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alat - MONITORING ALAT LABORATORIUM</title>
    <link rel="stylesheet" href="assets/style.css?v=2">
</head>
<body>
    <nav class="navbar">
        <h2>MONITORING ALAT LABORATORIUM</h2>
        <div class="menu">
            <a href="index.php">Dashboard</a>
            <a href="tambah.php">Tambah Alat Baru</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <main class="content-area">
        <div class="page-title">
            <div class="title-bar"></div>
            <h2>+ Edit Data Alat</h2>
        </div>
        
        <div class="form-container">
            <form action="" method="POST">
                <div class="form-group">
                    <label>Nama Alat</label>
                    <input type="text" name="nama_alat" class="form-input-text" value="<?php echo $d['nama_alat']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Merk</label>
                    <input type="text" name="merk" class="form-input-text" value="<?php echo $d['merk']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <div class="select-wrapper">
                        <select name="status" class="custom-select" required>
                            <option value="Baik" <?php if($d['status'] == 'Baik') echo 'selected'; ?>>BAIK</option>
                            <option value="Rusak" <?php if($d['status'] == 'Rusak') echo 'selected'; ?>>RUSAK</option>
                        </select>
                        <svg class="select-icon-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" name="update" class="btn-simpan-custom">SIMPAN</button>
                    <a href="index.php" style="margin-left: 15px; color: #96A78D; text-decoration: none; font-weight: 600;">BATAL</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>