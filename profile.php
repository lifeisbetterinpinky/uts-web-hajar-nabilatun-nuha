<?php 
session_start();

// Proteksi halaman, jika belum login, tendang ke login.php
if ($_SESSION['status'] != "login") {
    header("location:login.php");
    exit();
}

$user_level = $_SESSION['level'] ?? 'mahasiswa';

// Jika yang buka adalah admin, kembalikan ke index.php karena ini halaman khusus mahasiswa
if ($user_level == 'admin') {
    header("location:index.php");
    exit();
}

include 'config/koneksi.php';

// Ambil data mahasiswa yang sedang aktif login dari Session
$session_nama  = $_SESSION['username'] ?? '';
$session_nim   = $_SESSION['nim'] ?? '';
$session_prodi = $_SESSION['prodi'] ?? '';
$session_email = $_SESSION['email'] ?? ''; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Monitoring Alat Lab</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="asset/style.css?v=<?php echo time(); ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <nav class="navbar">
        <h2><i class="fas fa-microscope"></i> MONITORING ALAT LABORATORIUM</h2>
        <div class="menu">
            <a href="index.php"><i class="fas fa-house"></i> Dashboard</a>
            <a href="profile.php" class="active"><i class="fas fa-user-circle"></i> Profil Saya</a>
            <a href="#" onclick="confirmLogout()"><i class="fas fa-door-open"></i> Logout</a>
        </div>
    </nav>

    <main class="container">
        
        <a href="index.php" class="btn-kembali"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>

        <div class="profile-wrapper">
            
            <div class="profile-card">
                <div class="profile-avatar">
                    <i class="fas fa-circle-user"></i>
                </div>
                <h3 style="color: var(--text-dark); margin-bottom: 5px;"><?php echo htmlspecialchars($session_nama); ?></h3>
                <span style="background: #EBF8FF; color: #2B6CB0; padding: 4px 12px; border-radius: 15px; font-size: 0.8rem; font-weight: 700;">Mahasiswa Aktif</span>
                
                <div class="profile-details">
                    <div class="profile-info-group">
                        <label><i class="fas fa-id-card"></i> NIM (Nomor Induk Mahasiswa)</label>
                        <p><?php echo htmlspecialchars($session_nim); ?></p>
                    </div>
                    <div class="profile-info-group">
                        <label><i class="fas fa-graduation-cap"></i> Program Studi</label>
                        <p><?php echo htmlspecialchars($session_prodi); ?></p>
                    </div>
                    <?php if(!empty($session_email)): ?>
                    <div class="profile-info-group">
                        <label><i class="fas fa-envelope"></i> Email Terdaftar</label>
                        <p><?php echo htmlspecialchars($session_email); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="history-card">
                <h3 style="margin-top: 0; margin-bottom: 20px; color: var(--text-dark); border-bottom: 2px solid var(--primary); padding-bottom: 10px;">
                    <i class="fas fa-history" style="color: var(--primary);"></i> Riwayat Peminjaman Alat Saya
                </h3>
                
                <div class="table-wrapper" style="box-shadow: none; padding: 0;">
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">No</th>
                                <th>Alat yang Dipinjam</th>
                                <th style="text-align: center; width: 90px;">Jumlah</th>
                                <th style="text-align: center; width: 180px;">Tanggal Pinjam</th>
                                <th style="text-align: center; width: 150px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no_p = 1;
                            $query_p = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE nim = '$session_nim' ORDER BY id DESC");

                            if(mysqli_num_rows($query_p) == 0) {
                                echo "<tr><td colspan='5' style='text-align:center; color:#a0aec0; padding: 20px;'>Anda belum pernah melakukan peminjaman alat.</td></tr>";
                            }
                            
                            while($data_p = mysqli_fetch_array($query_p)){
                                $current_status = $data_p['status'] ?? 'Belum Disetujui';
                            ?>
                            <tr>
                                <td style="text-align: center; font-weight: 600; color: #a0aec0;"><?php echo $no_p++; ?></td>
                                <td style="font-weight: 600; color: var(--primary);"><?php echo htmlspecialchars($data_p['nama_alat']); ?></td>
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
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </main>

    <script>
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