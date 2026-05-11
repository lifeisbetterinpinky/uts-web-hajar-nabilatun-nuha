CREATE DATABASE db_lab_pku;
USE db_lab_pku;

CREATE TABLE alat_lab (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_alat VARCHAR(100) NOT NULL,
    merk VARCHAR(50),
    lokasi VARCHAR(50),
    status ENUM('Baik', 'Rusak', 'Maintenance') DEFAULT 'Baik',
    tgl_perawatan DATE
);