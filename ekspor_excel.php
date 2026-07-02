<?php
// Hubungkan ke database dan library PhpSpreadsheet
include 'config/koneksi.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls; // Menggunakan writer XLS asli
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// 1. Membuat object Spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// 2. Membuat Judul Besar di Baris 1
$sheet->setCellValue('A1', 'DATA INVENTARIS ALAT LABORATORIUM');
$sheet->mergeCells('A1:F1'); // Gabungkan kolom A sampai F
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// 3. Membuat Header Kolom Tabel di Baris 3
$headers = ['No', 'Nama Alat', 'Merk', 'Total', 'Kondisi Baik', 'Kondisi Rusak'];
$columns = ['A', 'B', 'C', 'D', 'E', 'F'];

foreach ($headers as $index => $header) {
    $colLetter = $columns[$index];
    $sheet->setCellValue($colLetter . '3', $header);
}

// Mewarnai Header Kolom (Warna Hijau Pastel sesuai desain awalmu #B6CEB4)
$sheet->getStyle('A3:F3')->getFont()->setBold(true);
$sheet->getStyle('A3:F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A3:F3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFB6CEB4');

// 4. Mengambil Data dari Database dan Memasukkannya ke Kolom
$no = 1;
$rowIdx = 4; // Data dimulai dari baris nomor 4
$query = mysqli_query($koneksi, "SELECT * FROM alat_lab");

while ($data = mysqli_fetch_array($query)) {
    $total = $data['jumlah_baik'] + $data['jumlah_rusak'];
    
    $sheet->setCellValue('A' . $rowIdx, $no++);
    $sheet->setCellValue('B' . $rowIdx, $data['nama_alat']);
    $sheet->setCellValue('C' . $rowIdx, $data['merk']);
    $sheet->setCellValue('D' . $rowIdx, $total);
    $sheet->setCellValue('E' . $rowIdx, $data['jumlah_baik'] . " Unit");
    $sheet->setCellValue('F' . $rowIdx, $data['jumlah_rusak'] . " Unit");
    
    // Mengatur alignment agar rapi tengah
    $sheet->getStyle('A' . $rowIdx)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D' . $rowIdx . ':F' . $rowIdx)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D' . $rowIdx)->getFont()->setBold(true); // Total dibuat Bold
    
    $rowIdx++;
}

// 5. Memberi Border (Garis Tabel) otomatis pada seluruh data yang terisi
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];
$sheet->getStyle('A3:F' . ($rowIdx - 1))->applyFromArray($styleArray);

// 6. Mengatur ukuran lebar kolom otomatis sesuai isi teksnya
foreach ($columns as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// 7. Proses pembuatan header download file .xls asli
$filename = "Data_Inventaris_Alat_Lab.xls";

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xls($spreadsheet);
$writer->save('php://output');
exit();
?>