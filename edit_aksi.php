<?php 
include 'config/koneksi.php';
session_start();

$id    = $_POST['id'];
$nama  = $_POST['nama_alat'];
$merk  = $_POST['merk'];
$total = $_POST['jumlah_total'];
$baik  = $_POST['jumlah_baik'];
$rusak = $_POST['jumlah_rusak'];

$query = "UPDATE alat_lab SET 
          nama_alat = '$nama', 
          merk = '$merk', 
          jumlah_total = '$total', 
          jumlah_baik = '$baik', 
          jumlah_rusak = '$rusak' 
          WHERE id = '$id'";

if(mysqli_query($koneksi, $query)){
    $_SESSION['notif'] = "Data berhasil diperbarui!";
    $_SESSION['notif_type'] = "success";
    header("location:index.php");
} else {
    $_SESSION['notif'] = "Gagal memperbarui data: " . mysqli_error($koneksi);
    $_SESSION['notif_type'] = "error";
    header("location:index.php");
}
?>