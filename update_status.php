<?php
session_start();
include 'config/koneksi.php';

// Cek apakah user sudah login dan berlevel admin
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['level'] != 'admin') {
    $_SESSION['notif'] = "Akses ditolak! Anda bukan admin.";
    $_SESSION['notif_type'] = "error";
    header("Location: index.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $status = mysqli_real_escape_string($koneksi, $_GET['status']);

    // Mengubah status peminjaman berdasarkan kolom id
    $query_update = mysqli_query($koneksi, "UPDATE peminjaman SET status = '$status' WHERE id = '$id'");

    if ($query_update) {
        $_SESSION['notif'] = "Status peminjaman berhasil diperbarui menjadi: " . $status;
        $_SESSION['notif_type'] = "success";
    } else {
        $_SESSION['notif'] = "Gagal memperbarui status di database.";
        $_SESSION['notif_type'] = "error";
    }
}

header("Location: index.php");
exit();
?>