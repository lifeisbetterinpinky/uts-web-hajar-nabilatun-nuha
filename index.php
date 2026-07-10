<?php 
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

if ($_SESSION['status'] != "login") {
    header("location:login.php");
    exit();
}

$user_level = $_SESSION['level'] ?? 'mahasiswa';
$session_nama  = $_SESSION['username'] ?? '';
$session_nim   = $_SESSION['nim'] ?? '';
$session_prodi = $_SESSION['prodi'] ?? '';
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
    <title>Monitoring Alat Lab - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="asset/style.css?v=<?php echo time(); ?>">
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
            <a href="index.php" class="active"><i class="fas fa-house"></i> Dashboard</a>
            
            <?php if($user_level == 'mahasiswa'): ?>
                <a href="profile.php"><i class="fas fa-user-circle"></i> Profil Saya</a>
            <?php endif; ?>
            
            <?php if($user_level == 'admin'): ?>
                <a href="index.php?action=tambah#popupForm" class="btn-tambah"><i class="fas fa-plus-circle"></i> Tambah Alat Baru</a>
            <?php endif; ?>

            <a href="#" onclick="confirmLogout()"><i class="fas fa-door-open"></i> Logout (<?php echo htmlspecialchars($session_nama); ?>)</a>
        </div>
    </nav>

    <main class="container">
        <h3>Daftar Inventaris Alat</h3>
        
        <div class="stats-container" style="margin-bottom: 25px;">
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
        
        <div class="export-container">
            <?php if($user_level == 'admin'): ?>
                <a href="ekspor_word.php" class="btn-export btn-word">
                    <i class="fas fa-file-word"></i> Ekspor ke Word
                </a>
                <a href="ekspor_excel.php" class="btn-export btn-excel">
                    <i class="fas fa-file-excel"></i> Ekspor ke Excel
                </a>
                <a href="index.php?action=import#popupImportExcel" class="btn-export btn-import-custom">
                    <i class="fas fa-file-import"></i> Import Excel
                </a>
            <?php else: ?>
                <a href="index.php?action=ajukan_pinjam#popupPinjamForm" class="btn-tambah" style="background-color: var(--primary); text-decoration: none; padding: 10px 20px; font-weight: 700; box-shadow: 0 4px 10px rgba(150, 167, 141, 0.3);">
                    <i class="fas fa-file-signature"></i> ISI FORMULIR PEMINJAMAN ALAT
                </a>
            <?php endif; ?>
        </div>

        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th style="width: 60px; text-align: center;">No</th>
                        <th>Nama Alat</th>
                        <th>Merk</th>
                        <th style="text-align: center; width: 100px;">Total</th>
                        <th style="text-align: center; width: 140px;">Kondisi Baik</th>
                        <th style="text-align: center; width: 140px;">Kondisi Rusak</th>
                        <?php if($user_level == 'admin'): ?>
                            <th style="text-align: center; width: 200px;">Aksi</th>
                        <?php endif; ?>
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
                        <td style="text-align: center; font-weight: 600; color: #a0aec0;"><?php echo $no++; ?></td>
                        <td style="font-weight: 600;"><?php echo $data['nama_alat']; ?></td>
                        <td><?php echo $data['merk']; ?></td>
                        <td style="text-align: center;"><strong><?php echo $total; ?></strong></td>
                        <td style="text-align: center;"><span class="badge baik"><?php echo $data['jumlah_baik']; ?> Unit</span></td>
                        <td style="text-align: center;"><span class="badge rusak"><?php echo $data['jumlah_rusak']; ?> Unit</span></td>
                        
                        <?php if($user_level == 'admin'): ?>
                            <td style="text-align: center;">
                                <div class="action-container">
                                    <a href="index.php?action=edit&id=<?php echo $data['id']; ?>#popupEditForm" class="btn-aksi btn-edit">
                                       <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="#" class="btn-aksi btn-hapus" onclick="confirmDelete('<?php echo $data['id']; ?>', '<?php echo htmlspecialchars($data['nama_alat'], ENT_QUOTES); ?>')">
                                       <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div> 
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php } ?> 
                </tbody>
            </table>
        </div>

        <?php if($user_level == 'admin'): ?>
        <div class="section-divider" style="margin-top: 40px;">
            <div class="section-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0;"><i class="fas fa-list-check"></i> Daftar Riwayat Peminjaman Mahasiswa</h3>
                <a href="ekspor_excel_pinjam.php" class="btn-export btn-excel btn-excel-dark">
                    <i class="fas fa-file-excel"></i> Ekspor Riwayat Pinjam (Excel)
                </a>
            </div>
            
            <div class="table-wrapper">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Prodi</th>
                            <th>Alat yang Dipinjam</th>
                            <th style="text-align: center; width: 90px;">Jumlah</th> 
                            <th style="text-align: center; width: 180px;">Tanggal & Waktu Pinjam</th>
                            <th style="text-align: center; width: 180px;">Status</th>
                            <th style="text-align: center; width: 180px;">Aksi Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no_p = 1;
                        $query_p = mysqli_query($koneksi, "SELECT * FROM peminjaman ORDER BY id DESC");

                        if(mysqli_num_rows($query_p) == 0) {
                            echo "<tr><td colspan='9' style='text-align:center; color:#a0aec0;'>Belum ada data riwayat peminjaman mahasiswa masuk.</td></tr>";
                        }
                        while($data_p = mysqli_fetch_array($query_p)){
                            $current_status = $data_p['status'] ?? 'Belum Disetujui';
                        ?>
                        <tr>
                            <td style="text-align: center; font-weight: 600; color: #a0aec0;"><?php echo $no_p++; ?></td>
                            <td style="font-weight: 600; color: var(--text-dark);"><?php echo $data_p['nim']; ?></td>
                            <td><?php echo $data_p['nama_mahasiswa']; ?></td>
                            <td><?php echo $data_p['prodi']; ?></td>
                            <td style="font-weight: 600; color: var(--primary);"><?php echo $data_p['nama_alat']; ?></td>
                            <td style="text-align: center;"><strong><?php echo $data_p['jumlah_pinjam']; ?></strong> Unit</td>
                            <td style="text-align: center; font-size: 0.85rem; color: #718096;"><?php echo date('d-m-Y H:i', strtotime($data_p['waktu_pinjam'])); ?> WIB</td>
                            
                            <td style="text-align: center;">
                                <?php if ($current_status == 'Belum Disetujui'): ?>
                                    <span style="background: #FEEBC8; color: #C05621; padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 0.8rem; display: inline-block;">Belum Disetujui</span>
                                <?php elseif ($current_status == 'Disetujui'): ?>
                                    <span style="background: #EBF8FF; color: #2B6CB0; padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 0.8rem; display: inline-block;">Disetujui (Dipinjam)</span>
                                <?php elseif ($current_status == 'Ditolak'): ?>
                                    <span style="background: #FED7D7; color: #C53030; padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 0.8rem; display: inline-block;">Ditolak</span>
                                <?php else: ?>
                                    <span style="background: #C6F6D5; color: #22543D; padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 0.8rem; display: inline-block;">Sudah Kembali</span>
                                <?php endif; ?>
                            </td>

                            <td style="text-align: center;">
                                <?php if ($current_status == 'Belum Disetujui'): ?>
                                    <a href="update_status.php?id=<?php echo $data_p['id']; ?>&status=Disetujui" style="background-color: #48BB78; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 0.8rem; font-weight: 600; margin-right: 4px; display: inline-block;">Setujui</a>
                                    <a href="update_status.php?id=<?php echo $data_p['id']; ?>&status=Ditolak" style="background-color: #E53E3E; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 0.8rem; font-weight: 600; display: inline-block;">Tolak</a>
                                <?php elseif ($current_status == 'Disetujui'): ?>
                                    <a href="update_status.php?id=<?php echo $data_p['id']; ?>&status=Sudah Dikembalikan" style="background-color: #3182CE; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 0.8rem; font-weight: 600; display: inline-block;">Konfirmasi Kembali</a>
                                <?php else: ?>
                                    <span style="color: #a0aec0; font-size: 0.85rem; font-style: italic;">Selesai (No Action)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

    </main>

    <?php
    if (isset($_GET['action']) && $_GET['action'] == 'ajukan_pinjam') {
        date_default_timezone_set("Asia/Jakarta");
        $waktu_sekarang = date("Y-m-d\TH:i"); 
        $waktu_tampilan = date("d-m-Y H:i");
    ?>
    <div id="popupPinjamForm" class="modal-overlay" style="display: flex !important;">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3><i class="fas fa-file-signature"></i> Formulir Peminjaman Alat</h3>
                <a href="index.php" class="close-btn">&times;</a>
            </div>
            <form action="pinjam_aksi.php" method="POST">
                <div class="form-group">
                    <label>Nama Lengkap Mahasiswa</label>
                    <input type="text" name="nama_mahasiswa" class="form-control form-control-readonly" value="<?php echo htmlspecialchars($session_nama); ?>" readonly>
                </div>
                <div class="form-group">
                    <label>NIM (Nomor Induk Mahasiswa)</label>
                    <input type="text" name="nim" class="form-control form-control-readonly" value="<?php echo htmlspecialchars($session_nim); ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Asal Program Studi (Prodi)</label>
                    <input type="text" name="prodi" class="form-control form-control-readonly" value="<?php echo htmlspecialchars($session_prodi); ?>" readonly>
                </div>
                
                <div class="form-group">
                    <label>Pilih Alat & Jumlah yang Dipinjam</label>
                    <div class="alat-selection-container">
                        <?php 
                        $query_list_alat = mysqli_query($koneksi, "SELECT nama_alat, jumlah_baik FROM alat_lab WHERE jumlah_baik > 0");
                        if(mysqli_num_rows($query_list_alat) == 0) {
                            echo "<p style='color: #718096; font-size: 0.85rem; margin: 5px 0;'>Tidak ada alat berkondisi baik yang tersedia.</p>";
                        }
                        while($alat = mysqli_fetch_array($query_list_alat)) {
                            $nama_clean = htmlspecialchars($alat['nama_alat'], ENT_QUOTES);
                        ?>
                        <div class="alat-checkbox-row">
                            <div class="alat-checkbox-left">
                                <input type="checkbox" name="alat_dipilih[]" value="<?php echo $nama_clean; ?>" id="chk_<?php echo md5($nama_clean); ?>">
                                <label style="margin: 0; font-weight: 500;" for="chk_<?php echo md5($nama_clean); ?>"><?php echo $nama_clean; ?></label>
                            </div>
                            <input type="number" name="jumlah_<?php echo md5($nama_clean); ?>" class="input-jumlah-pinjam" min="1" max="<?php echo $alat['jumlah_baik']; ?>" value="1">
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tanggal & Waktu Pengajuan</label>
                    <input type="hidden" name="waktu_pinjam" value="<?php echo $waktu_sekarang; ?>">
                    <input type="text" class="form-control form-control-readonly" value="<?php echo $waktu_tampilan; ?> WIB" readonly>
                </div>
                
                <div class="modal-footer">
                    <a href="index.php" class="btn-aksi btn-edit" style="text-decoration: none;">Batal</a>
                    <button type="submit" class="btn-simpan-custom">Ajukan Peminjaman</button>
                </div>
            </form>
        </div>
    </div>
    <?php } ?>

    <?php if (isset($_GET['action']) && $_GET['action'] == 'import' && $user_level == 'admin'): ?>
    <div id="popupImportExcel" class="modal-overlay" style="display: flex !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-file-import"></i> Import Excel</h3>
                <a href="index.php" class="close-btn">&times;</a>
            </div>
            <form action="import_excel_aksi.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label">Pilih File Excel (.xlsx / .xls)</label>
                    <input type="file" name="excel_file" class="form-input-text" accept=".xlsx,.xls" required>
                </div>
                <div class="modal-footer">
                    <a href="index.php" class="btn-aksi btn-edit" style="text-decoration: none;">BATAL</a>
                    <button type="submit" class="btn-simpan-custom">IMPORT DATA</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['action']) && $_GET['action'] == 'tambah' && $user_level == 'admin'): ?>
    <div id="popupForm" class="modal-overlay" style="display: flex !important;">
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
                    <a href="index.php" class="btn-aksi btn-edit" style="text-decoration: none;">Batal</a>
                    <button type="submit" class="btn-simpan-custom">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <?php
    if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']) && $user_level == 'admin') {
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
                    <a href="index.php" class="btn-aksi btn-edit" style="text-decoration: none;">Batal</a>
                    <button type="submit" class="btn-simpan-custom">Simpan Perubahan</button>
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
        function confirmDelete(id, namaAlat) {
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Alat '" + namaAlat + "' akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e53e3e',
                cancelButtonColor: '#718096',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => { if (result.isConfirmed) { window.location.href = 'hapus.php?id=' + id; } });
        }
        function confirmLogout() {
            Swal.fire({
                title: 'Ingin Keluar Sistem?',
                text: "Sesi Anda saat ini akan diakhiri.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#96A78D',
                cancelButtonColor: '#718096',
                confirmButtonText: 'Ya, Logout!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => { if (result.isConfirmed) { window.location.href = 'logout.php'; } });
        }
    </script>
</body>
</html>