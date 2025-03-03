<?php
// 1. Panggil file koneksi database
include 'config.php';

// 2. Periksa apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 3. Ambil data dari form
    $id_siswa = $_POST["id_siswa"];
    $tanggal = $_POST["tanggal"];
    $status = $_POST["status"];
    $keterangan = $_POST["keterangan"];

    // 4. Validasi data (PENTING!)
    if (!empty($id_siswa) && !empty($tanggal) && !empty($status)) {

        // Sanitasi Data (PENTING!)
        $id_siswa = mysqli_real_escape_string($conn, $id_siswa);
        $tanggal = mysqli_real_escape_string($conn, $tanggal);
        $status = mysqli_real_escape_string($conn, $status);
        $keterangan = mysqli_real_escape_string($conn, $keterangan);

        // 5. Query untuk menambahkan data presensi ke database
        $sql = "INSERT INTO presensi (id_siswa, tanggal, status, keterangan) VALUES ('$id_siswa', '$tanggal', '$status', '$keterangan')";

        // 6. Jalankan query
        if (mysqli_query($conn, $sql)) {
            // 7. Jika berhasil, redirect ke halaman form presensi dengan status sukses
            header("Location: presensi_form.php?sukses=1"); // Tambahkan parameter sukses
            exit();
        } else {
            // 8. Jika gagal, tampilkan pesan error
            $error = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $error = "Semua field wajib diisi!";
    }
} else {
    // Jika bukan method POST, redirect ke halaman form presensi
    header("Location: presensi_form.php");
    exit();
}

// 9. Tutup koneksi database
mysqli_close($conn);
?>
