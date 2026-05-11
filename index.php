<?php include 'config/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring Alat Lab - UTS</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <nav>
        <h2>Sistem Monitoring Lab</h2>
        <a href="tambah.php">Tambah Alat Baru</a>
    </nav>

    <main>
        <h3>Daftar Inventaris Alat</h3>
        <table border="1" cellpadding="10">
            <tr>
                <th>No</th>
                <th>Nama Alat</th>
                <th>Merk</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            </table>
    </main>
</body>
</html>