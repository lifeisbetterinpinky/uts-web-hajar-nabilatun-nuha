<?php
session_start();
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nama_mahasiswa'])) {
    $nama_mahasiswa = mysqli_real_escape_string($koneksi, $_POST['nama_mahasiswa']);
    $nim            = mysqli_real_escape_string($koneksi, $_POST['nim']);
    $prodi          = mysqli_real_escape_string($koneksi, $_POST['prodi']);
    $waktu_pinjam   = mysqli_real_escape_string($koneksi, $_POST['waktu_pinjam']);

    if (isset($_POST['alat_dipilih']) && is_array($_POST['alat_dipilih'])) {
        
        $array_alat_kalimat = array();
        $total_akumulasi_pinjam = 0; // Variabel baru untuk menampung total jumlah

        foreach ($_POST['alat_dipilih'] as $nama_alat) {
            $key_md5 = md5($nama_alat);
            $jumlah_pinjam = isset($_POST['jumlah_' . $key_md5]) ? (int)$_POST['jumlah_' . $key_md5] : 1;

            // Tambahkan jumlah alat yang dipinjam ke total akumulasi
            $total_akumulasi_pinjam += $jumlah_pinjam;

            // Masukkan ke array dengan format: "Nama Alat (X Unit)"
            $array_alat_kalimat[] = $nama_alat . " (" . $jumlah_pinjam . " Unit)";

            // Update/potong stok alat_lab secara otomatis
            $query_update_stok = "UPDATE alat_lab SET jumlah_baik = jumlah_baik - $jumlah_pinjam WHERE nama_alat = '$nama_alat'";
            mysqli_query($koneksi, $query_update_stok);
        }

        // Gabungkan array menjadi satu string panjang dipisahkan tanda koma
        $alat_gabungan = mysqli_real_escape_string($koneksi, implode(", ", $array_alat_kalimat));

        // Simpan ke database (Sekarang kolom jumlah_pinjam diisi dengan $total_akumulasi_pinjam)
        $query_insert = "INSERT INTO peminjaman (nim, nama_mahasiswa, prodi, nama_alat, jumlah_pinjam, waktu_pinjam) 
                         VALUES ('$nim', '$nama_mahasiswa', '$prodi', '$alat_gabungan', '$total_akumulasi_pinjam', '$waktu_pinjam')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            $_SESSION['notif'] = "Peminjaman alat laboratorium berhasil diajukan!";
            $_SESSION['notif_type'] = "success";
        } else {
            $_SESSION['notif'] = "Gagal menyimpan data peminjaman.";
            $_SESSION['notif_type'] = "error";
        }

    } else {
        $_SESSION['notif'] = "Silakan pilih minimal satu alat yang ingin dipinjam!";
        $_SESSION['notif_type'] = "warning";
    }
}

header("location:index.php");
exit();
?>