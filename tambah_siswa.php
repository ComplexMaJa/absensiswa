<?php
// 1. Panggil file koneksi database
include 'config.php';

// 2. Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nisn = $_POST['nisn'];
    $nama_siswa = $_POST['nama_siswa'];
    $kelas = $_POST['kelas'];

    // Check if NISN already exists
    $sql_check_nisn = "SELECT * FROM siswa WHERE nisn = '$nisn'";
    $result_check = mysqli_query($conn, $sql_check_nisn);

    if (mysqli_num_rows($result_check) > 0) {
        echo "NISN sudah terdaftar! Silakan gunakan NISN lain.<br>";
    } else {
        // Handle file upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $photo = $_FILES['photo'];

            // Get file details
            $photo_name = $photo['name'];
            $photo_tmp_name = $photo['tmp_name'];
            $photo_extension = pathinfo($photo_name, PATHINFO_EXTENSION);

            // Check if the uploaded file is a GIF, JPG, PNG, or JPEG
            if (in_array(strtolower($photo_extension), ['gif', 'jpg', 'jpeg', 'png'])) {
                $photo_new_name = "uploads/" . uniqid('', true) . '.' . $photo_extension;
                move_uploaded_file($photo_tmp_name, $photo_new_name);

                // Insert data into the database
                $sql = "INSERT INTO siswa (nisn, nama_siswa, kelas, photo) VALUES ('$nisn', '$nama_siswa', '$kelas', '$photo_new_name')";
                if (mysqli_query($conn, $sql)) {
                    echo "Siswa berhasil ditambahkan!";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "Hanya file GIF, JPG, JPEG, dan PNG yang diperbolehkan!";
            }
        } elseif (isset($_POST['photo']) && !empty($_POST['photo'])) {
            // If the photo was captured from the camera, store it as a base64 image
            $base64_image = $_POST['photo'];
            $image_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64_image));

            // Generate a unique name for the photo
            $photo_new_name = "uploads/" . uniqid('', true) . ".png";

            // Save the image
            file_put_contents($photo_new_name, $image_data);

            // Insert data into the database
            $sql = "INSERT INTO siswa (nisn, nama_siswa, kelas, photo) VALUES ('$nisn', '$nama_siswa', '$kelas', '$photo_new_name')";
            if (mysqli_query($conn, $sql)) {
                echo "Siswa berhasil ditambahkan!";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "Terjadi kesalahan saat mengupload file!<br>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
    <style>
        /* Basic Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background-color: #222;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
            margin: 20px;
        }

        h2 {
            text-align: center;
            color: #ffcc00;
            margin-bottom: 20px;
        }

        input[type="text"], input[type="file"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #444;
            background-color: #333;
            color: #fff;
            font-size: 16px;
        }

        button {
            background-color: #ffcc00;
            color: #111;
            border: none;
            padding: 14px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        button:hover {
            background-color: #ff9900;
        }

        .camera-container {
            text-align: center;
            margin: 20px 0;
        }

        video {
            width: 100%;
            max-width: 300px;
            border-radius: 10px;
            margin-top: 10px;
            display: none; /* Camera video will show only when 'Ambil Foto' is clicked */
        }

        #capture-btn {
            background-color: #ffcc00;
            color: #111;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }

        #capture-btn:hover {
            background-color: #ff9900;
        }

        canvas {
            display: none;
        }

        /* Add styling for the back button */
        .back-button {
            background-color: #333;
            color: #ffcc00;
            border: 2px solid #ffcc00;
            padding: 12px 0;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 15px;
            text-align: center;
            text-decoration: none;
            display: block;
        }

        .back-button:hover {
            background-color: #ffcc00;
            color: #111;
        }

        /* Container for both buttons */
        .button-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Tambah Siswa</h2>
    
    <form method="POST" enctype="multipart/form-data">
        <label for="nisn">NISN</label>
        <input type="text" name="nisn" required>
        
        <label for="nama_siswa">Nama Siswa</label>
        <input type="text" name="nama_siswa" required>
        
        <label for="kelas">Kelas</label>
        <input type="text" name="kelas" required>

        <label for="photo">Pilih Foto atau Gunakan Kamera</label>
        <input type="file" name="photo" accept="image/gif, image/jpeg, image/png" id="file-upload">
        
        <div class="camera-container">
            <label for="camera">Atau Gunakan Kamera</label>
            <br>
            <video id="video" autoplay></video>
            <br>
            <button type="button" id="capture-btn">Ambil Foto</button>
            <canvas id="canvas" style="display: none;"></canvas>
            <input type="hidden" name="photo" id="photo">
        </div>

        <div class="button-container">
            <button type="submit">Simpan</button>
            <a href="tampilkan_siswa.php" class="back-button">Kembali ke Daftar Siswa</a>
        </div>
    </form>
</div>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const captureBtn = document.getElementById('capture-btn');
    const photoInput = document.getElementById('photo');
    
    // Hide the video stream initially
    video.style.display = 'none';

    captureBtn.addEventListener('click', function() {
        // Display the video element when 'Ambil Foto' is clicked
        video.style.display = 'block';

        // Access the camera when the user clicks "Ambil Foto"
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    video.srcObject = stream;
                })
                .catch(function(error) {
                    alert("Tidak dapat mengakses kamera!");
                });
        }

        // Set canvas dimensions equal to the video frame once the video is ready
        video.addEventListener('loadeddata', function() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
        });

        // Capture the photo
        canvas.getContext('2d').drawImage(video, 0, 0);

        // Convert the captured image to a base64 string and assign to the hidden input
        const imageData = canvas.toDataURL('image/png');
        photoInput.value = imageData;
    });
</script>

</body>
</html>

<?php
// 5. Tutup koneksi database
mysqli_close($conn);
?>