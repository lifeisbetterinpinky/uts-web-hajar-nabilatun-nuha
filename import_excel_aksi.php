<?php
include 'config/koneksi.php';
require 'vendor/autoload.php';

session_start();

// Helper untuk mendeteksi apakah suatu teks berisi kode JS/CSS atau XML (markup sampah bawaan Excel HTML)
function is_code_or_markup($str) {
    $str = strtolower($str);
    $patterns = [
        '{', '}', ';', 'szhtml', 'frames[', 'document.', 'open(', 'write(', 'a:link', 'a:visited', 
        'text-decoration', 'cursor:', 'vml', 'xml', 'xmlns', 'excel', 'microsoft', 'behavior:',
        'margin-bottom', 'padding:', 'background:', 'font-family', 'border:', 'display:', 'content:'
    ];
    
    foreach ($patterns as $pattern) {
        if (strpos($str, $pattern) !== false) {
            return true;
        }
    }
    return false;
}

// 1. Validasi session login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:login.php");
    exit();
}

// 1. Validasi file 'excel_file' ada dan tidak corrupt
if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                title: 'Gagal!',
                text: 'File belum dipilih atau upload gagal.',
                icon: 'error',
                confirmButtonColor: '#B6CEB4'
            }).then(() => {
                window.location.href = 'import_excel.php';
            });
        </script>
    </body>
    </html>";
    exit();
}

$tmpPath = $_FILES['excel_file']['tmp_name'];
$fileName = $_FILES['excel_file']['name'];

if (!is_uploaded_file($tmpPath) || filesize($tmpPath) === 0) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                title: 'Gagal!',
                text: 'File kosong atau tidak valid.',
                icon: 'error',
                confirmButtonColor: '#B6CEB4'
            }).then(() => {
                window.location.href = 'import_excel.php';
            });
        </script>
    </body>
    </html>";
    exit();
}

// 2. Validasi ekstensi agar mendukung 'xlsx' dan 'xls'
$ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
if ($ext !== 'xlsx' && $ext !== 'xls') {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Ekstensi file tidak didukung. Harus .xlsx atau .xls',
                icon: 'error',
                confirmButtonColor: '#B6CEB4'
            }).then(() => {
                window.location.href = 'import_excel.php';
            });
        </script>
    </body>
    </html>";
    exit();
}

// 3. Sebelum memuat file ke IOFactory, gunakan setValueBinder untuk memastikan data terikat dengan benar
\PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());

// Mencegah dan meredam potensi error DOMDocument loadHTML dengan menonaktifkan error internal XML/HTML
libxml_use_internal_errors(true);

try {
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($tmpPath);
    $reader->setReadDataOnly(true);
    $spreadsheet = @$reader->load($tmpPath);
} catch (\Exception $e) {
    libxml_clear_errors();
    echo "<!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Gagal memproses file Excel: " . addslashes($e->getMessage()) . "',
                icon: 'error',
                confirmButtonColor: '#B6CEB4'
            }).then(() => {
                window.location.href = 'import_excel.php';
            });
        </script>
    </body>
    </html>";
    exit();
}

libxml_clear_errors();

$worksheet = $spreadsheet->getActiveSheet();
$highestRow = $worksheet->getHighestRow();
$highestColumn = $worksheet->getHighestColumn();
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

// Cari baris header secara dinamis
$headerRow = 1; 
$colNama = 'A';
$colMerk = 'B';
$colBaik = 'C';
$colRusak = 'D';

for ($rowIdx = 1; $rowIdx <= 10; $rowIdx++) {
    $tempColNama = null;
    $tempColMerk = null;
    $tempColBaik = null;
    $tempColRusak = null;
    
    for ($col = 1; $col <= $highestColumnIndex; $col++) {
        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        $cellVal = strtolower(trim((string)$worksheet->getCell($colLetter . $rowIdx)->getValue()));
        
        if ($cellVal === '') {
            continue;
        }
        
        if (strpos($cellVal, 'nama') !== false || strpos($cellVal, 'alat') !== false) {
            $tempColNama = $colLetter;
        } elseif (strpos($cellVal, 'merk') !== false || strpos($cellVal, 'brand') !== false) {
            $tempColMerk = $colLetter;
        } elseif (strpos($cellVal, 'baik') !== false) {
            $tempColBaik = $colLetter;
        } elseif (strpos($cellVal, 'rusak') !== false) {
            $tempColRusak = $colLetter;
        }
    }
    
    if ($tempColNama !== null && ($tempColMerk !== null || $tempColBaik !== null || $tempColRusak !== null)) {
        $headerRow = $rowIdx;
        $colNama = $tempColNama;
        if ($tempColMerk !== null) $colMerk = $tempColMerk;
        if ($tempColBaik !== null) $colBaik = $tempColBaik;
        if ($tempColRusak !== null) $colRusak = $tempColRusak;
        break;
    }
}

$inserted = 0;
$updated = 0;
$skipped = 0;

for ($row = $headerRow + 1; $row <= $highestRow; $row++) {
    $namaRaw = $worksheet->getCell($colNama . $row)->getValue();
    $merkRaw = $worksheet->getCell($colMerk . $row)->getValue();
    $baikRaw = $worksheet->getCell($colBaik . $row)->getValue();
    $rusakRaw = $worksheet->getCell($colRusak . $row)->getValue();

    $nama = preg_replace('/\s+/', ' ', trim((string)$namaRaw));
    $merk = preg_replace('/\s+/', ' ', trim((string)$merkRaw));

    if ($nama === '') {
        continue;
    }

    if (is_code_or_markup($nama) || is_code_or_markup($merk)) {
        continue;
    }

    $baikClean = preg_replace('/[^0-9]/', '', (string)$baikRaw);
    $rusakClean = preg_replace('/[^0-9]/', '', (string)$rusakRaw);

    $baik = ($baikClean !== '') ? (int)$baikClean : 0;
    $rusak = ($rusakClean !== '') ? (int)$rusakClean : 0;
    $total = $baik + $rusak;

    $stmt = mysqli_prepare($koneksi, "SELECT id FROM alat_lab WHERE TRIM(nama_alat)=? AND TRIM(merk)=? LIMIT 1");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ss', $nama, $merk);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $existing = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);

        if ($existing && isset($existing['id'])) {
            $id = (int)$existing['id'];
            $stmt2 = mysqli_prepare($koneksi, "UPDATE alat_lab SET jumlah_total=?, jumlah_baik=?, jumlah_rusak=? WHERE id=?");
            if ($stmt2) {
                mysqli_stmt_bind_param($stmt2, 'iiii', $total, $baik, $rusak, $id);
                if (mysqli_stmt_execute($stmt2)) {
                    $updated++;
                } else {
                    $skipped++;
                }
                mysqli_stmt_close($stmt2);
            } else {
                $skipped++;
            }
        } else {
            $stmt3 = mysqli_prepare($koneksi, "INSERT INTO alat_lab (nama_alat, merk, jumlah_total, jumlah_baik, jumlah_rusak) VALUES (?,?,?,?,?)");
            if ($stmt3) {
                mysqli_stmt_bind_param($stmt3, 'ssiii', $nama, $merk, $total, $baik, $rusak);
                if (mysqli_stmt_execute($stmt3)) {
                    $inserted++;
                } else {
                    $skipped++;
                }
                mysqli_stmt_close($stmt3);
            } else {
                $skipped++;
            }
        }
    } else {
        $skipped++;
    }
}

// ==========================================
// 6. TAMPILKAN POP-UP DAN REDIRECT SETELAHNYA
// ==========================================
$pesan_notif = "Import Selesai! Data baru: $inserted, Update: $updated, Dilewati: $skipped.";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Proses Import...</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background-color: #f3f4f6; font-family: sans-serif;">

    <script>
        // Memunculkan Pop-up SweetAlert2 secara langsung
        Swal.fire({
            title: 'Berhasil!',
            text: '<?php echo $pesan_notif; ?>',
            icon: 'success',
            confirmButtonColor: '#B6CEB4', // Menyesuaikan warna tema webmu
            confirmButtonText: 'OK'
        }).then((result) => {
            // Setelah tombol OK diklik, halaman akan diarahkan langsung ke Dashboard utama (index.php)
            if (result.isConfirmed) {
                window.location.href = 'index.php';
            }
        });
    </script>

</body>
</html>