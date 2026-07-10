<?php
include 'config/koneksi.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// 1. Judul Dokumen Excel
$sheet->setCellValue('A1', 'LAPORAN DATA PEMINJAMAN ALAT LABORATORIUM');
$sheet->mergeCells('A1:G1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// 2. Header Tabel
$headers = ['No', 'NIM', 'Nama Mahasiswa', 'Program Studi', 'Nama Alat', 'Jumlah Pinjam', 'Tanggal & Waktu'];
$columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];

foreach ($headers as $index => $header) {
    $colLetter = $columns[$index];
    $sheet->setCellValue($colLetter . '3', $header);
}

// Mewarnai Header Tabel (Warna Hijau Tema)
$sheet->getStyle('A3:G3')->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
$sheet->getStyle('A3:G3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF96A78D');
$sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// 3. Mengisi Baris Data dari Mysql
$rowIdx = 4;
$no = 1;
$query = mysqli_query($koneksi, "SELECT * FROM peminjaman ORDER BY id DESC");

while ($data = mysqli_fetch_array($query)) {
    $sheet->setCellValue('A' . $rowIdx, $no++);
    $sheet->setCellValueExplicit('B' . $rowIdx, $data['nim'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->setCellValue('C' . $rowIdx, $data['nama_mahasiswa']);
    $sheet->setCellValue('D' . $rowIdx, $data['prodi']);
    $sheet->setCellValue('E' . $rowIdx, $data['nama_alat']);
    $sheet->setCellValue('F' . $rowIdx, $data['jumlah_pinjam'] . " Unit");
    $sheet->setCellValue('G' . $rowIdx, date('d-m-Y H:i', strtotime($data['waktu_pinjam'])) . " WIB");
    
    $sheet->getStyle('A' . $rowIdx)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F' . $rowIdx . ':G' . $rowIdx)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    
    $rowIdx++;
}

// 4. Set Border Tipis Otomatis
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];
if ($rowIdx > 4) {
    $sheet->getStyle('A3:G' . ($rowIdx - 1))->applyFromArray($styleArray);
}

// 5. Auto width kolom
foreach ($columns as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// 6. Set Header HTTP untuk Pengunduhan Instan Browser
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Laporan_Peminjaman_Alat_Lab.xls"');
header('Cache-Control: max-age=0');

$writer = new Xls($spreadsheet);
$writer->save('php://output');
exit();
?>