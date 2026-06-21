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
    status ENUM('Baik', 'Rusak') DEFAULT 'Baik'
);

CREATE TABLE pengaduan_kerusakan (
   id INT(11) NOT NULL AUTO_INCREMENT,
   id_alat INT(11) NOT NULL,
   id_user INT(11) NOT NULL,
   deskripsi_kendala TEXT NOT NULL,
   tgl_lapor DATE NOT NULL,
   status ENUM('Belum Diperbaiki', 'Proses', 'Selesai') NOT NULL DEFAULT 'Belum Diperbaiki',
   PRIMARY KEY (id )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
