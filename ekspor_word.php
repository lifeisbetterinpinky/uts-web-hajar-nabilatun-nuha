<?php
// Hubungkan ke database
include 'config/koneksi.php';

// Mengirimkan header agar browser mendownload sebagai file Word (.doc)
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=Laporan_Inventaris_Alat_Lab.doc");
header("Pragma: no-cache");
header("Expires: 0");
?>

<h2 style="text-align: center;">LAPORAN MONITORING INVENTARIS ALAT LABORATORIUM</h2>
<br>

<table border="1" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #B6CEB4;">
            <th style="padding: 10px;">No</th>
            <th style="padding: 10px;">Nama Alat</th>
            <th style="padding: 10px;">Merk</th>
            <th style="padding: 10px;">Total</th>
            <th style="padding: 10px;">Kondisi Baik</th>
            <th style="padding: 10px;">Kondisi Rusak</th>
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
            <td style="padding: 8px; text-align: center;"><?php echo $no++; ?></td>
            <td style="padding: 8px;"><?php echo $data['nama_alat']; ?></td>
            <td style="padding: 8px;"><?php echo $data['merk']; ?></td>
            <td style="padding: 8px; text-align: center;"><strong><?php echo $total; ?></strong></td>
            <td style="padding: 8px; text-align: center;"><?php echo $data['jumlah_baik']; ?> Unit</td>
            <td style="padding: 8px; text-align: center;"><?php echo $data['jumlah_rusak']; ?> Unit</td>
        </tr>
        <?php } ?>
    </tbody>
</table>