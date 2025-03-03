<?php
// 1. Panggil file koneksi database
include 'config.php';

// 2. Query untuk mengambil data siswa
$sql = "SELECT * FROM siswa";
$result = mysqli_query($conn, $sql);

// 3. Periksa apakah query berhasil
if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

// 4. Inisialisasi variabel untuk menampung pesan error dan sukses
$error = "";
$sukses = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 5. Ambil data dari form
    $id_siswa = $_POST["id_siswa"];
    $tanggal = $_POST["tanggal"];
    $status = implode(", ", $_POST["status"]); // To handle multiple checked values (for checklist)
    $keterangan = $_POST["keterangan"];

    // 6. Validasi data
    if (!empty($id_siswa) && !empty($tanggal) && !empty($status)) {
        // Sanitasi Data
        $id_siswa = mysqli_real_escape_string($conn, $id_siswa);
        $tanggal = mysqli_real_escape_string($conn, $tanggal);
        $status = mysqli_real_escape_string($conn, $status);
        $keterangan = mysqli_real_escape_string($conn, $keterangan);

        // 7. Query untuk menambahkan data presensi ke database
        $sql = "INSERT INTO presensi (id_siswa, tanggal, status, keterangan) VALUES ('$id_siswa', '$tanggal', '$status', '$keterangan')";

        // 8. Jalankan query
        if (mysqli_query($conn, $sql)) {
            $sukses = "Presensi berhasil disimpan!";
        } else {
            $error = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $error = "Semua field wajib diisi!";
    }
}

// 9. Tutup koneksi database
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Presensi Siswa</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #000000; /* AMOLED Black background */
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 800px;
            background-color: #121212; /* Dark background */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
        }

        h2 {
            text-align: center;
            color: #ffcc00; /* Bright color */
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 18px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #444;
            background-color: #333;
            color: #fff;
            margin-top: 10px;
        }

        .form-group select {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #444;
            background-color: #333;
            color: #fff;
            margin-top: 10px;
        }

        .status-checklist {
            margin-top: 20px;
        }

        .status-checklist label {
            display: block;
            font-size: 18px;
            margin: 5px 0;
            cursor: pointer;
            padding-left: 20px;
        }

        .status-checklist input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            vertical-align: middle;
        }

        .button-group {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .button-group input[type="submit"] {
            background-color: #ffcc00;
            color: #000;
            font-size: 18px;
            border-radius: 8px;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button-group input[type="submit"]:hover {
            background-color: #ff9900;
        }

        .success, .error {
            color: #ffcc00;
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
        }

        .error {
            color: #ff4444;
        }

        .success {
            color: #44ff44;
        }

        .back-button {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
        }

        .back-button a {
            color: #ffcc00;
            text-decoration: none;
            font-weight: bold;
        }

        .back-button a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Form Presensi Siswa</h2>

    <?php if ($sukses): ?>
        <p class="success"><?php echo $sukses; ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">

        <div class="form-group">
            <label for="id_siswa">Pilih Siswa</label>
            <select name="id_siswa" id="id_siswa" required>
                <option value="">-- Pilih Siswa --</option>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['id_siswa'] . "'>" . $row['nama_siswa'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" required min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>">
        </div>

        <div class="status-checklist">
            <label>Checklist Status Presensi:</label>
            <label><input type="checkbox" name="status[]" value="Hadir"> Hadir</label>
            <label><input type="checkbox" name="status[]" value="Tidak Hadir"> Tidak Hadir</label>
            <label><input type="checkbox" name="status[]" value="Sakit"> Sakit</label>
            <label><input type="checkbox" name="status[]" value="Izin"> Izin</label>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="4" placeholder="Masukkan keterangan (opsional)" style="width: 100%; padding: 12px; font-size: 16px; border-radius: 8px; border: 1px solid #444; background-color: #333; color: #fff;"></textarea>
        </div>

        <div class="button-group">
            <input type="submit" value="Simpan Presensi">
        </div>
    </form>

    <div class="back-button">
        <a href="tampilkan_siswa.php">Kembali ke Daftar Siswa</a>
    </div>
</div>

</body>
</html>
