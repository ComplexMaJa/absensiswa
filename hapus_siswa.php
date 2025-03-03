<?php
// 1. Panggil file koneksi database
include 'config.php';

// 2. Periksa apakah ID siswa ada di URL
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $id_siswa = $_GET["id"];

    // 3. Hapus data yang tergantung di tabel presensi (menghapus presensi siswa terlebih dahulu)
    $delete_presensi = "DELETE FROM presensi WHERE id_siswa = " . $id_siswa;
    if (!mysqli_query($conn, $delete_presensi)) {
        // Jika gagal menghapus presensi
        echo "Error deleting presensi: " . mysqli_error($conn);
        exit();
    }

    // 4. Query untuk menghapus data siswa
    $sql = "DELETE FROM siswa WHERE id_siswa = " . $id_siswa;

    // 5. Jalankan query
    if (mysqli_query($conn, $sql)) {
        // 6. Jika berhasil, redirect ke halaman daftar siswa
        header("Location: tampilkan_siswa.php");
        exit();
    } else {
        // 7. Jika gagal, tampilkan pesan error
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
} else {
    // 8. Jika ID tidak ada, redirect ke halaman daftar siswa
    header("Location: tampilkan_siswa.php");
    exit();
}

// 9. Tutup koneksi database
mysqli_close($conn);
?>
