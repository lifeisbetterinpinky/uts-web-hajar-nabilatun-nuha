<?php 
session_start();

if ($_SESSION['status'] != "login") {
    header("location:login.php");
    exit();
}
?>
<?php include 'config/koneksi.php'; ?>
<?php
$query_total = mysqli_query($koneksi, "SELECT SUM(jumlah_total) AS total FROM alat_lab");
$data_total = mysqli_fetch_assoc($query_total);

$query_baik = mysqli_query($koneksi, "SELECT SUM(jumlah_baik) AS total_baik FROM alat_lab");
$data_baik = mysqli_fetch_assoc($query_baik);

$query_rusak = mysqli_query($koneksi, "SELECT SUM(jumlah_rusak) AS total_rusak FROM alat_lab");
$data_rusak = mysqli_fetch_assoc($query_rusak);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Alat Lab - UTS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="assets/style.css?v=1.5">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <?php if(isset($_SESSION['notif'])): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let tipe = "<?php echo $_SESSION['notif_type']; ?>";
            let pesan = "<?php echo $_SESSION['notif']; ?>";
            Swal.fire({
                icon: tipe,
                title: tipe === "success" ? "Berhasil!" : "Oops...",
                text: pesan,
                confirmButtonColor: "#96A78D"
            });
        });
    </script>
    <?php 
        unset($_SESSION['notif']); 
        unset($_SESSION['notif_type']);
    endif; 
    ?>

    <nav class="navbar">
        <h2><i class="fas fa-microscope"></i> MONITORING ALAT LABORATORIUM</h2>
        <div class="menu">
            <a href="index.php"><i class="fas fa-house"></i> Dashboard</a>
            <a href="index.php?action=tambah#popupForm" class="btn-tambah"><i class="fas fa-plus-circle"></i> Tambah Alat Baru</a>
            <a href="logout.php" onclick="return confirm('Yakin ingin logout?')"><i class="fas fa-door-open"></i> Logout</a>
        </div>
    </nav>

    <main class="container">
        <h3>Daftar Inventaris Alat</h3>
        
        <table class="styled-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Alat</th>
                    <th>Merk</th>
                    <th style="text-align: center;">Total</th>
                    <th style="text-align: center;">Kondisi Baik</th>
                    <th style="text-align: center;">Kondisi Rusak</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($koneksi, "SELECT * FROM alat_lab");
                while($data = mysqli_fetch_array($query)){
                    $total = $data['jumlah_baik'] + $data['jumlah_rusak']; 
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $data['nama_alat']; ?></td>
                    <td><?php echo $data['merk']; ?></td>
                    <td style="text-align: center;"><strong><?php echo $total; ?></strong></td>
                    <td style="text-align: center;"><span class="badge baik"><?php echo $data['jumlah_baik']; ?> Unit</span></td>
                    <td style="text-align: center;"><span class="badge rusak"><?php echo $data['jumlah_rusak']; ?> Unit</span></td>
                    <td style="text-align: center;">
                        <div class="action-container">
                            <a href="index.php?action=edit&id=<?php echo $data['id']; ?>#popupEditForm" class="btn-aksi btn-edit">
                               <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="hapus.php?id=<?php echo $data['id']; ?>" class="btn-aksi btn-hapus" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i> Hapus</a>
                        </div> 
                    </td>
                </tr>
                <?php } ?> 
            </tbody>
        </table>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon icon-total"><i class="fas fa-boxes-stacked"></i></div>
                <div class="stat-info">
                    <h3>Total Alat</h3>
                    <p><?php echo $data_total['total'] ?? 0; ?> <span>Unit</span></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-baik"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <h3>Kondisi Baik</h3>
                    <p><?php echo $data_baik['total_baik'] ?? 0; ?> <span>Unit</span></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-rusak"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-info">
                    <h3>Kondisi Rusak</h3>
                    <p><?php echo $data_rusak['total_rusak'] ?? 0; ?> <span>Unit</span></p>
                </div>
            </div>
        </div>
    </main>

    <div id="popupForm" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-plus-circle"></i> Tambah Alat Baru</h3>
                <a href="index.php" class="close-btn">&times;</a>
            </div>
            <form action="tambah_aksi.php" method="POST">
                <div class="form-group">
                    <label>Nama Alat</label>
                    <input type="text" name="nama_alat" class="form-control" required placeholder="Ketik Nama Alat">
                </div>
                <div class="form-group">
                    <label>Merk</label>
                    <input type="text" name="merk" class="form-control" required placeholder="Ketik Merk Alat">
                </div>
                <div class="form-group">
                    <label>Jumlah Kondisi Baik</label>
                    <input type="number" id="pop_baik" name="jumlah_baik" class="form-control" required min="0" value="0" oninput="hitungTotalTambah()">
                </div>
                <div class="form-group">
                    <label>Jumlah Kondisi Rusak</label>
                    <input type="number" id="pop_rusak" name="jumlah_rusak" class="form-control" required min="0" value="0" oninput="hitungTotalTambah()">
                </div>
                <input type="hidden" id="pop_total" name="jumlah_total" value="0">
                <div class="modal-footer">
                    <a href="index.php" class="btn-aksi btn-hapus" style="text-decoration: none; padding: 10px 15px; line-height: 20px;">Batal</a>
                    <button type="submit" class="btn-tambah" style="border: none; cursor: pointer; padding: 10px 15px; background-color: #96A78D; color: white; border-radius: 6px;">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
        $id_edit = $_GET['id'];
        $query_edit = mysqli_query($koneksi, "SELECT * FROM alat_lab WHERE id='$id_edit'");
        $data_edit = mysqli_fetch_array($query_edit);
        
        if ($data_edit) {
            $total_edit = $data_edit['jumlah_baik'] + $data_edit['jumlah_rusak'];
    ?>
    <div id="popupEditForm" class="modal-overlay" style="display: flex !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Edit Data Alat</h3>
                <a href="index.php" class="close-btn">&times;</a>
            </div>
            <form action="edit_aksi.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $data_edit['id']; ?>">

                <div class="form-group">
                    <label>Nama Alat</label>
                    <input type="text" name="nama_alat" class="form-control" value="<?php echo $data_edit['nama_alat']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Merk</label>
                    <input type="text" name="merk" class="form-control" value="<?php echo $data_edit['merk']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Jumlah Kondisi Baik</label>
                    <input type="number" id="edit_baik" name="jumlah_baik" class="form-control" value="<?php echo $data_edit['jumlah_baik']; ?>" required min="0" oninput="hitungTotalEdit()">
                </div>
                <div class="form-group">
                    <label>Jumlah Kondisi Rusak</label>
                    <input type="number" id="edit_rusak" name="jumlah_rusak" class="form-control" value="<?php echo $data_edit['jumlah_rusak']; ?>" required min="0" oninput="hitungTotalEdit()">
                </div>
                
                <input type="hidden" id="edit_total" name="jumlah_total" value="<?php echo $total_edit; ?>">
                
                <div class="modal-footer">
                    <a href="index.php" class="btn-aksi btn-hapus" style="text-decoration: none; padding: 10px 15px; line-height: 20px;">Batal</a>
                    <button type="submit" class="btn-tambah" style="border: none; cursor: pointer; padding: 10px 15px; background-color: #96A78D; color: white; border-radius: 6px;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    <?php 
        }
    } 
    ?>

    <script>
        function hitungTotalTambah() {
            let baik = parseInt(document.getElementById('pop_baik').value) || 0;
            let rusak = parseInt(document.getElementById('pop_rusak').value) || 0;
            document.getElementById('pop_total').value = baik + rusak;
        }
        function hitungTotalEdit() {
            let baik = parseInt(document.getElementById('edit_baik').value) || 0;
            let rusak = parseInt(document.getElementById('edit_rusak').value) || 0;
            document.getElementById('edit_total').value = baik + rusak;
        }
    </script>
</body>
</html>