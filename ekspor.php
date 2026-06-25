<?php
// 1. Load library otomatis dari Composer
require 'vendor/autoload.php';

// 2. Inisialisasi object PHPWord
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// 3. Buat halaman baru (Section) di dokumen
$section = $phpWord->addSection();

// 4. Tambahkan teks atau konten di dalamnya
$section->addText(
    "Halo, ini adalah hasil ekspor dokumen Word!",
    array('name' => 'Arial', 'size' => 14, 'bold' => true)
);
$section->addText("Data ini digenerate otomatis menggunakan PHPWord.");

// 5. Atur Header HTTP agar browser mengenali ini sebagai file Word yang harus diunduh
$filename = "dokumen_ekspor.docx";
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

// 6. Tulis data dan langsung download melalui browser
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('php://output');
exit;