<?php 
session_start();

if ($_SESSION['status'] != "login") {
    header("location:login.php");
    exit();
}
?>

<?php include 'config/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Alat Lab - UTS</title>
    <link rel="stylesheet" href="assets/style.css?V=1.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            // Rumus hitung total otomatis
            $total = $data['jumlah_baik'] + $data['jumlah_rusak']; 
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $data['nama_alat']; ?></td>
            <td><?php echo $data['merk']; ?></td>
            <td style="text-align: center;"><strong><?php echo $total; ?></strong></td>
            <td style="text-align: center;">
                <span class="badge baik"><?php echo $data['jumlah_baik']; ?> Unit</span>
            </td>
            <td style="text-align: center;">
                <span class="badge rusak"><?php echo $data['jumlah_rusak']; ?> Unit</span>
            </td>
                    <td style="text-align: center;">
                        <div class="action-container">
                            <a href="edit.php?id=<?php echo $data['id']; ?>" class="btn-aksi btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="hapus.php?id=<?php echo $data['id']; ?>" class="btn-aksi btn-hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                         </div> 
                     </td>
                 </tr>
                 <?php } ?> 
             </tbody>
         </table>
     </main>
 </body>
 </html>