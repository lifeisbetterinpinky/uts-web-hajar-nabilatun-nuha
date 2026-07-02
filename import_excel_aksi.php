<?php
include 'config/koneksi.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

session_start();

if ($_SESSION['status'] != "login") {
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
$ext = strtolower(pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION));

// Mengizinkan file Excel asli (.xlsx atau .xls)
if (!in_array($ext, ['xlsx', 'xls'])) {
    $_SESSION['notif'] = "Ekstensi file tidak didukung. Gunakan .xlsx atau .xls";
    $_SESSION['notif_type'] = "error";
    header("location:import_excel.php");
    exit();
}

try {
    $spreadsheet = IOFactory::load($tmpPath);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray(null, true, true, true);

    if (!$rows || count($rows) < 2) {
        throw new Exception("File Excel kosong atau tidak valid.");
    }

    $inserted = 0;
    $updated = 0;
    $skipped = 0;

    $i = 0;
    foreach ($rows as $row) {
        $i++;
        
        // Lewati baris 1 dan 2 (Judul besar dan nama header kolom)
        if ($i < 3) {
            continue;
        }

        // Ambil data berdasarkan urutan kolom Excel kamu (B=Nama, C=Merk, E=Baik, F=Rusak)
        $nama = isset($row['B']) ? trim((string)$row['B']) : '';
        $merk = isset($row['C']) ? trim((string)$row['C']) : '';
        $baikRaw = isset($row['E']) ? trim((string)$row['E']) : '0';
        $rusakRaw = isset($row['F']) ? trim((string)$row['F']) : '0';

        // FILTER KETAT BARIS SAMPAH:
        // Jika nama mengandung tanda +, atau kurung kurawal {, atau kata 'total', atau kosong -> LANGSUNG BUANG!
        if ($nama === '' || strpos($nama, '+') !== false || strpos($nama, '{') !== false || strpos($nama, 'szhtml') !== false || strtolower($nama) == 'total' || strtolower($nama) == 'nama alat') {
            $skipped++;
            continue;
        }

        // Bersihkan angka dari kata " Unit" jika ada
        $baikClean = preg_replace('/[^0-9]/', '', $baikRaw);
        $rusakClean = preg_replace('/[^0-9]/', '', $rusakRaw);

        $baik = ($baikClean !== '') ? (int)$baikClean : 0;
        $rusak = ($rusakClean !== '') ? (int)$rusakClean : 0;
        $total = $baik + $rusak;

        // Amankan string untuk query database
        $nama_db = mysqli_real_escape_string($koneksi, $nama);
        $merk_db = mysqli_real_escape_string($koneksi, $merk);

        // Cek apakah data sudah ada di database
        $stmt = $koneksi->prepare("SELECT id FROM alat_lab WHERE nama_alat = ? AND merk = ? LIMIT 1");
        $stmt->bind_param('ss', $nama_db, $merk_db);
        $stmt->execute();
        $res = $stmt->get_result();
        $existing = $res ? $res->fetch_assoc() : null;
        $stmt->close();

        if ($existing && isset($existing['id'])) {
            // Jika sudah ada -> UPDATE
            $id = (int)$existing['id'];
            $stmt2 = $koneksi->prepare("UPDATE alat_lab SET jumlah_total=?, jumlah_baik=?, jumlah_rusak=? WHERE id=?");
            $stmt2->bind_param('iiii', $total, $baik, $rusak, $id);
            $stmt2->execute();
            $stmt2->close();
            $updated++;
        } else {
            // Jika data baru yang kamu ketik -> INSERT
            $stmt3 = $koneksi->prepare("INSERT INTO alat_lab (nama_alat, merk, jumlah_total, jumlah_baik, jumlah_rusak) VALUES (?, ?, ?, ?, ?)");
            $stmt3->bind_param('ssiii', $nama_db, $merk_db, $total, $baik, $rusak);
            $stmt3->execute();
            $stmt3->close();
            $inserted++;
        }
    }

    $_SESSION['notif'] = "Import Berhasil! Data Baru: $inserted, Diupdate: $updated, Sampah Dibuang: $skipped.";
    $_SESSION['notif_type'] = "success";

} catch (Exception $e) {
    $_SESSION['notif'] = "Gagal memproses file Excel: " . $e->getMessage();
    $_SESSION['notif_type'] = "error";
}

header("location:import_excel.php");
exit();