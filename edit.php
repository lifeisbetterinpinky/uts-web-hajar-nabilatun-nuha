<?php 
include 'config/koneksi.php';
session_start();


if ($_SESSION['status'] != "login") {
    header("location:login.php");
}


$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM alat_lab WHERE id='$id'");
$d = mysqli_fetch_array($query);

if (isset($_POST['update'])) {
    $nama   = $_POST['nama_alat'];
    $merk   = $_POST['merk'];
    $status = $_POST['status'];

    $update = mysqli_query($koneksi, "UPDATE alat_lab SET nama_alat='$nama', merk='$merk', status='$status' WHERE id='$id'");
    
    if ($update) {
        header("location:index.php?pesan=berhasil_update");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Alat - MONITORING ALAT LABORATORIUM</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <nav class="navbar">
        <h2>MONITORING ALAT LABORATORIUM</h2>
        <div class="menu">
            <a href="index.php">Dashboard</a>
        </div>
    </nav>

    <div class="content-area">
        <div class="page-title">
            <div class="title-bar"></div>
            <h2>+ Edit Data Alat</h2>
        </div>

        <div class="form-container">
            <form action="" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Alat</label>
                        <input type="text" name="nama_alat" class="form-input-text" value="<?php echo $d['nama_alat']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Merk</label>
                        <input type="text" name="merk" class="form-input-text" value="<?php echo $d['merk']; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div class="select-wrapper">
                        <select name="status" class="custom-select" required>
                            <option value="Baik" <?php if($d['status'] == 'Baik') echo 'selected'; ?>>Baik</option>
                            <option value="Rusak" <?php if($d['status'] == 'Rusak') echo 'selected'; ?>>Rusak</option>
                        </select>
                        <svg class="select-icon-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" name="update" class="btn-simpan-custom">Update Data</button>
                    <a href="index.php" style="margin-left:10px; color:#777; text-decoration:none;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>