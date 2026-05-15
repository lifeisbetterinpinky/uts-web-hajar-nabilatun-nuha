<?php 
include 'config/koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama_alat'];
$merk = $_POST['merk'];
$baik = $_POST['jumlah_baik'];
$rusak = $_POST['jumlah_rusak'];

$query = "UPDATE alat_lab SET 
          nama_alat='$nama', 
          merk='$merk', 
          jumlah_baik='$baik', 
          jumlah_rusak='$rusak' 
          WHERE id='$id'";

if(mysqli_query($koneksi, $query)){
    header("location:index.php?pesan=update_berhasil");
} else {
    echo "Gagal update data: " . mysqli_error($koneksi);
}
?>