<?php 
include 'config/koneksi.php';

$nama  = $_POST['nama_alat'];
$merk  = $_POST['merk'];
$total = $_POST['jumlah_total'];
$baik  = $_POST['jumlah_baik'];
$rusak = $_POST['jumlah_rusak'];

$query = "INSERT INTO alat_lab (nama_alat, merk, jumlah_total, jumlah_baik, jumlah_rusak) 
          VALUES ('$nama', '$merk', '$total', '$baik', '$rusak')";

if(mysqli_query($koneksi, $query)){
    header("location:index.php?pesan=input_berhasil");
} else {
    echo "Gagal menyimpan data: " . mysqli_error($koneksi);
}
?>