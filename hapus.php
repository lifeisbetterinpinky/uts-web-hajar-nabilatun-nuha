<?php 
include 'config/koneksi.php';
session_start();

$id = $_GET['id'];

$result = mysqli_query($koneksi, "DELETE FROM alat_lab WHERE id='$id'");

if ($result) {
    $_SESSION['notif'] = "Alat berhasil dihapus!";
    $_SESSION['notif_type'] = "success";
    header("Location:index.php");
} else {
    $_SESSION['notif'] = "Gagal menghapus data!";
    $_SESSION['notif_type'] = "error";
    header("Location:index.php");
}
?>