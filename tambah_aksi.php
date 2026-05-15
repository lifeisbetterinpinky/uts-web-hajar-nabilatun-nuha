<?php 
include 'config/koneksi.php';

$nama = $_POST['nama_alat'];
$merk = $_POST['merk'];
$baik = $_POST['jumlah_baik'];
$rusak = $_POST['jumlah_rusak'];

$query = "INSERT INTO alat_lab (nama_alat, merk, jumlah_baik, jumlah_rusak) 
          VALUES ('$nama', '$merk', '$baik', '$rusak')";

if(mysqli_query($koneksi, $query)){
    header("location:index.php?pesan=input_berhasil");
} else {
    echo "Gagal menyimpan data: " . mysqli_error($koneksi);
}
?>