<?php

include 'config/koneksi.php';
$id = $_GET['id'];
$result = mysqli_query($koneksi, "DELETE FROM alat_lab WHERE id=$id");
if ($result) {
    header("Location:index.php?pesan=hapus_berhasil");
} else {
    header("Location:index.php?pesan=hapus_gagal");
}
?>