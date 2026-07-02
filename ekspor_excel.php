<?php
// Hubungkan ke database
include 'config/koneksi.php';

// Nama file yang akan didownload (Ganti ekstensinya menjadi .csv)
$filename = "Data_Inventaris_Alat_Lab.csv";

// Header untuk memberi tahu browser bahwa ini adalah file CSV murni
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// Membuka output buffer untuk menulis data CSV
$output = fopen("php://output", "w");

// 1. Membuat Header Kolom Tabel Excel (Gunakan pembatas titik koma ';')
fputcsv($output, array('No', 'Nama Alat', 'Merk', 'Total', 'Kondisi Baik', 'Kondisi Rusak'), ';');

// 2. Mengambil data dari database
$no = 1;
$query = mysqli_query($koneksi, "SELECT * FROM alat_lab");

while ($data = mysqli_fetch_array($query)) {
    $total = $data['jumlah_baik'] + $data['jumlah_rusak'];
    
    // Masukkan baris data ke dalam array
    $row = array(
        $no++,
        $data['nama_alat'],
        $data['merk'],
        $total,
        $data['jumlah_baik'] . " Unit",
        $data['jumlah_rusak'] . " Unit"
    );
    
    // Tulis baris data tersebut ke dalam file CSV
    fputcsv($output, $row, ';');
}

// Tutup output buffer
fclose($output);
exit();
?>