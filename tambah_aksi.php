<?php 
include 'config/koneksi.php';
session_start(); 

$nama  = $_POST['nama_alat'];
$merk  = $_POST['merk'];
$total = $_POST['jumlah_total']; 
$baik  = $_POST['jumlah_baik'];
$rusak = $_POST['jumlah_rusak'];

$query = "INSERT INTO alat_lab (nama_alat, merk, jumlah_total, jumlah_baik, jumlah_rusak) 
          VALUES ('$nama', '$merk', '$total', '$baik', '$rusak')";

if(mysqli_query($koneksi, $query)){
    $_SESSION['notif'] = "Data berhasil ditambahkan!";
    $_SESSION['notif_type'] = "success";
    header("location:index.php");
} else {
    $_SESSION['notif'] = "Gagal menyimpan data: " . mysqli_error($koneksi);
    $_SESSION['notif_type'] = "error";
    header("location:index.php");
}
?>