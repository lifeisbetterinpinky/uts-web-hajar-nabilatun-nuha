<?php
include 'config/koneksi.php';
require 'vendor/autoload.php';

session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php");
    exit();
}

if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['notif'] = "File belum dipilih atau upload gagal.";
    $_SESSION['notif_type'] = "error";
    header("location:import_excel.php");
    exit();
}

$tmpPath = $_FILES['excel_file']['tmp_name'];
$fileContent = file_get_contents($tmpPath);

$inserted = 0;
$updated = 0;
$skipped = 0;

// DETEKSI OTOMATIS: Jika file aslinya berisi struktur HTML <table> (Efek Ekspor Web)
if (strpos($fileContent, '<table') !== false || strpos($fileContent, '<tr') !== false) {
    
    // Gunakan DOMDocument native PHP untuk membongkar baris tabel HTML
    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($fileContent, 'HTML-ENTITIES', 'UTF-8'));
    $rows = $dom->getElementsByTagName('tr');
    
    $i = 0;
    foreach ($rows as $row) {
        $i++;
        $cols = $row->getElementsByTagName('td');
        if ($cols->length == 0) {
            $cols = $row->getElementsByTagName('th'); // jika menggunakan th
        }
        
        // Skip baris judul utama, spasi kosong, atau header tabel (No, Nama Alat, dll)
        if ($i < 3 || $cols->length < 6) {
            continue;
        }

        // Ambil data berdasarkan susunan kolom tabel ekspor kamu
        $nama  = mysqli_real_escape_string($koneksi, trim($cols->item(1)->nodeValue));
        $merk  = mysqli_real_escape_string($koneksi, trim($cols->item(2)->nodeValue));
        
        // Hilangkan tulisan " Unit" jika ada agar tersisa angkanya saja
        $baik_text  = str_replace(' Unit', '', trim($cols->item(4)->nodeValue));
        $rusak_text = str_replace(' Unit', '', trim($cols->item(5)->nodeValue));
        
        $baik  = (int)$baik_text;
        $rusak = (int)$rusak_text;
        $total = $baik + $rusak;

        // Validasi agar baris sampah atau total bawah tidak ikut ter-input
        if (empty($nama) || is_numeric($nama) || strtolower($nama) == 'nama alat' || strtolower($nama) == 'total') {
            $skipped++;
            continue;
        }

        // Cek data di database
        $check = mysqli_query($koneksi, "SELECT id FROM alat_lab WHERE nama_alat='$nama' AND merk='$merk' LIMIT 1");
        if (mysqli_num_rows($check) > 0) {
            $data_exist = mysqli_fetch_assoc($check);
            $id = $data_exist['id'];
            if (mysqli_query($koneksi, "UPDATE alat_lab SET jumlah_total=$total, jumlah_baik=$baik, jumlah_rusak=$rusak WHERE id=$id")) {
                $updated++;
            }
        } else {
            if (mysqli_query($koneksi, "INSERT INTO alat_lab (nama_alat, merk, jumlah_total, jumlah_baik, jumlah_rusak) VALUES ('$nama', '$merk', $total, $baik, $rusak)")) {
                $inserted++;
            }
        }
    }
} 
// JIKA FILE ADALAH EXCEL ASLI (.XLSX atau .XLS Murni)
else {
    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmpPath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $i = 0;
        foreach ($rows as $row) {
            $i++;
            $nama  = isset($row['B']) ? mysqli_real_escape_string($koneksi, trim((string)$row['B'])) : '';
            $merk  = isset($row['C']) ? mysqli_real_escape_string($koneksi, trim((string)$row['C'])) : '';
            $baik  = isset($row['E']) ? (int)$row['E'] : 0;
            $rusak = isset($row['F']) ? (int)$row['F'] : 0;
            $total = $baik + $rusak;

            if ($i < 3 || empty($nama) || is_numeric($nama) || strtolower($nama) == 'nama alat' || strtolower($nama) == 'total') {
                $skipped++;
                continue;
            }

            $check = mysqli_query($koneksi, "SELECT id FROM alat_lab WHERE nama_alat='$nama' AND merk='$merk' LIMIT 1");
            if (mysqli_num_rows($check) > 0) {
                $data_exist = mysqli_fetch_assoc($check);
                $id = $data_exist['id'];
                if (mysqli_query($koneksi, "UPDATE alat_lab SET jumlah_total=$total, jumlah_baik=$baik, jumlah_rusak=$rusak WHERE id=$id")) {
                    $updated++;
                }
            } else {
                if (mysqli_query($koneksi, "INSERT INTO alat_lab (nama_alat, merk, jumlah_total, jumlah_baik, jumlah_rusak) VALUES ('$nama', '$merk', $total, $baik, $rusak)")) {
                    $inserted++;
                }
            }
        }
    } catch (Exception $e) {
        $_SESSION['notif'] = "Gagal membaca berkas Excel murni: " . $e->getMessage();
        $_SESSION['notif_type'] = "error";
        header("location:index.php");
        exit();
    }
}

$_SESSION['notif'] = "Import Selesai! Data Baru: $inserted, Diupdate: $updated, Baris Dilewati: $skipped.";
$_SESSION['notif_type'] = "success";

header("location:index.php");
exit();