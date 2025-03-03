<?php
// 1. Panggil file koneksi database
include 'config.php';

// Inisialisasi variabel error
$error = "";

// 2. Periksa apakah ID siswa ada di URL
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $id_siswa = $_GET["id"];

    // 3. Ambil data siswa berdasarkan ID
    $sql = "SELECT * FROM siswa WHERE id_siswa = " . $id_siswa;
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $nisn = $row["nisn"];
        $nama_siswa = $row["nama_siswa"];
        $kelas = $row["kelas"];
        $photo = $row["photo"]; // Get the current photo from the database
    } else {
        // Jika ID tidak valid atau data tidak ditemukan, redirect ke halaman daftar siswa
        header("Location: tampilkan_siswa.php");
        exit();
    }
} else {
    // Jika ID tidak ada, redirect ke halaman daftar siswa
    header("Location: tampilkan_siswa.php");
    exit();
}

// 4. Proses form ketika disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nisn = $_POST["nisn"];
    $nama_siswa = $_POST["nama_siswa"];
    $kelas = $_POST["kelas"];
    
    // Validasi data (SANGAT PENTING!) - tambahkan validasi yang lebih ketat
    if (!empty($nisn) && !empty($nama_siswa) && !empty($kelas)) {

        //Sanitasi Data (PENTING!)
        $nisn = mysqli_real_escape_string($conn, $nisn);
        $nama_siswa = mysqli_real_escape_string($conn, $nama_siswa);
        $kelas = mysqli_real_escape_string($conn, $kelas);

        // Check if an image is uploaded
        if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
            // Handle file upload
            $photo = $_FILES["photo"];
            $photo_name = $photo['name'];
            $photo_tmp_name = $photo['tmp_name'];
            $photo_extension = pathinfo($photo_name, PATHINFO_EXTENSION);

            // Check for allowed file types
            if (in_array(strtolower($photo_extension), ['gif', 'jpg', 'jpeg', 'png'])) {
                // Create a unique name for the photo
                $photo_new_name = "uploads/" . uniqid('', true) . '.' . $photo_extension;
                move_uploaded_file($photo_tmp_name, $photo_new_name);

                // If a new image is uploaded, update the photo path
                $sql = "UPDATE siswa SET nisn = '$nisn', nama_siswa = '$nama_siswa', kelas = '$kelas', photo = '$photo_new_name' WHERE id_siswa = " . $id_siswa;
            } else {
                $error = "Hanya file GIF, JPG, JPEG, dan PNG yang diperbolehkan!";
            }
        } else {
            // If no new photo is uploaded, keep the existing photo
            $sql = "UPDATE siswa SET nisn = '$nisn', nama_siswa = '$nama_siswa', kelas = '$kelas' WHERE id_siswa = " . $id_siswa;
        }

        // Execute the query to update the student's data
        if (mysqli_query($conn, $sql)) {
            // Jika berhasil, redirect ke halaman daftar siswa
            header("Location: tampilkan_siswa.php");
            exit();
        } else {
            $error = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $error = "Semua field wajib diisi!";
    }
}

// Tutup koneksi database (ditempatkan setelah HTML)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000000; /* AMOLED Black */
            color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background-color: #111111; /* Dark Background */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            margin: 20px;
        }

        h2 {
            color: #ffcc00;
            text-align: center;
            font-size: 32px;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .error {
            color: red;
            font-size: 18px;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="text"], input[type="file"], input[type="submit"] {
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            border: 1px solid #444444;
            background-color: #222222;
            color: #f5f5f5;
        }

        input[type="submit"] {
            background-color: #ffcc00;
            color: #111;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #ff9900;
        }

        a {
            color: #ffcc00;
            text-decoration: none;
            text-align: center;
            font-size: 18px;
            display: block;
            margin-top: 20px;
        }

        a:hover {
            text-decoration: underline;
        }

        img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Siswa</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id_siswa); ?>" enctype="multipart/form-data">
        NISN: <input type="text" name="nisn" value="<?php echo htmlspecialchars($nisn); ?>"><br><br>
        Nama Siswa: <input type="text" name="nama_siswa" value="<?php echo htmlspecialchars($nama_siswa); ?>"><br><br>
        Kelas: <input type="text" name="kelas" value="<?php echo htmlspecialchars($kelas); ?>"><br><br>

        <!-- Display current photo -->
        <?php if (!empty($photo)): ?>
            <p>Current Photo:</p>
            <img src="<?php echo $photo; ?>" alt="Current Photo">
        <?php endif; ?>

        <!-- Input for uploading a new photo -->
        <label for="photo">Upload New Photo (optional):</label>
        <input type="file" name="photo" accept="image/*"><br><br>

        <input type="submit" value="Simpan">
    </form>

    <br>
    <a href="tampilkan_siswa.php">Kembali ke Daftar Siswa</a>
</div>

</body>
</html>

<?php
// Pastikan ini ada di *luar* tag HTML terakhir
mysqli_close($conn);
?>
