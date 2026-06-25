<?php
// Hubungkan ke database
include 'config/koneksi.php';

// Mengirimkan header agar browser mendownload sebagai file Excel (.xls)
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data_Inventaris_Alat_Lab.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<h2 style="text-align: center;">DATA INVENTARIS ALAT LABORATORIUM</h2>
<br>

<table border="1">
    <thead>
        <tr style="background-color: #B6CEB4; font-weight: bold;">
            <th>No</th>
            <th>Nama Alat</th>
            <th>Merk</th>
            <th>Total</th>
            <th>Kondisi Baik</th>
            <th>Kondisi Rusak</th>
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
            <td style="text-align: center;"><?php echo $no++; ?></td>
            <td><?php echo $data['nama_alat']; ?></td>
            <td><?php echo $data['merk']; ?></td>
            <td style="text-align: center;"><strong><?php echo $total; ?></strong></td>
            <td style="text-align: center;"><?php echo $data['jumlah_baik']; ?> Unit</td>
            <td style="text-align: center;"><?php echo $data['jumlah_rusak']; ?> Unit</td>
        </tr>
        <?php } ?>
    </tbody>
</table>