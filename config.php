<?php
// Konfigurasi Database
$host     = "localhost";       // Host database (biasanya localhost)
$username = "root";            // Username database
$password = "";                // Password database (kosong jika tidak ada)
$database = "db_presensi";    // Nama database yang telah dibuat

// Membuat koneksi
$conn = mysqli_connect($host, $username, $password, $database);

// Memeriksa koneksi
if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}
// Jika koneksi berhasil, Anda bisa menambahkan pesan (opsional):
// echo "Koneksi berhasil!";

