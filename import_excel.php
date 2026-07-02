<?php
session_start();

if ($_SESSION['status'] != "login") {
    header("location:login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel - MONITORING ALAT LABORATORIUM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css?v=1.1">
</head>
<body>
    <nav class="navbar">
        <h2><i class="fas fa-microscope"></i> MONITORING ALAT LABORATORIUM</h2>
        <div class="menu">
            <a href="index.php">
                <i class="fas fa-house"></i> Dashboard
            </a>
            <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">
                <i class="fas fa-door-open"></i> Logout
            </a>
        </div>
    </nav>

    <main class="container">
        <h3>Import Data Inventaris dari Excel</h3>

        <div class="form-container">
            <form action="import_excel_aksi.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label">Pilih File Excel (.xlsx / .xls)</label>
                    <input type="file" name="excel_file" class="form-input-text" accept=".xlsx,.xls" required>
                    <small style="display:block; margin-top:8px; color:#6b7280;">
                        Pastikan menggunakan file <b>.xls</b> hasil dari menu Ekspor Web yang sudah kamu tambahkan datanya secara manual.
                    </small>
                </div>

                <div style="margin-top: 20px; display:flex; gap:12px;">
                    <button type="submit" class="btn-simpan-custom">IMPORT</button>
                    <a href="index.php" class="btn-aksi btn-hapus" style="text-decoration: none; display:inline-flex; align-items:center;">BATAL</a>
                </div>
            </form>
        </div>

        <?php if(isset($_SESSION['notif'])): ?>
            <div style="margin-top:18px; padding:12px; border-radius:8px; background:#fff; border:1px solid #e5e7eb;">
                <b style="color: <?php echo ($_SESSION['notif_type'] ?? 'success') === 'success' ? '#16a34a' : '#dc2626'; ?>">
                    <?php echo ($_SESSION['notif_type'] ?? 'success') === 'success' ? 'Berhasil!' : 'Gagal!'; ?>
                </b>
                <div style="margin-top:6px;">
                    <?php echo htmlspecialchars($_SESSION['notif']); ?>
                </div>
            </div>
            <?php unset($_SESSION['notif']); unset($_SESSION['notif_type']); ?>
        <?php endif; ?>
    </main>
</body>
</html>