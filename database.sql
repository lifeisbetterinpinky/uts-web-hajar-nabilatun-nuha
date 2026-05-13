CREATE DATABASE db_lab_pku;
USE db_lab_pku;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE alat_lab (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_alat VARCHAR(100) NOT NULL,
    merk VARCHAR(50),
    lokasi VARCHAR(50),
    status ENUM('Baik', 'Rusak') DEFAULT 'Baik',
    tgl_perawatan DATE
);