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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Siswa</title>
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
            max-width: 1000px;
            background-color: #121212; /* Dark background */
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
            margin: 20px;
        }

        h2 {
            text-align: center;
            color: #ffcc00; /* Bright color */
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .button {
            background-color: #ffcc00;
            color: #111;
            border: none;
            padding: 12px 24px; /* Smaller button size */
            font-size: 16px; /* Slightly smaller font size */
            font-weight: bold;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            width: 45%;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #ff9900;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #444;
            font-size: 18px;
        }

        th {
            background-color: #333333;
        }

        tr:nth-child(even) {
            background-color: #222;
        }

        tr:hover {
            background-color: #444;
        }

        a {
            color: #ffcc00;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .card {
            background-color: #333;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .action-buttons a {
            background-color: #ffcc00;
            color: #111;
            padding: 8px 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .action-buttons a:hover {
            background-color: #ff9900;
        }

        /* Optional: Add a fixed width for the images */
        .student-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Daftar Siswa</h2>

    <!-- Button Group -->
    <div class="button-group">
        <a href="tambah_siswa.php" class="button">Tambah Siswa Baru</a>
        <a href="presensi_form.php" class="button">Isi Presensi Siswa</a>
    </div>

    <!-- Table containing students -->
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Foto</th> <!-- New Column for Photo -->
                    <th>Aksi</th> <!-- Kolom untuk Aksi -->
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1; // Initialize counter starting from 1
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $counter . "</td>"; // Display the counter instead of actual id_siswa
                    echo "<td>" . $row["nisn"] . "</td>";
                    echo "<td>" . $row["nama_siswa"] . "</td>";
                    echo "<td>" . $row["kelas"] . "</td>";

                    // Check if the photo exists in the database and display it
                    if ($row["photo"]) {
                        echo "<td><img src='" . $row["photo"] . "' alt='Foto' class='student-photo'></td>";
                    } else {
                        echo "<td>No photo</td>";
                    }

                    echo "<td class='action-buttons'>
                            <a href='edit_siswa.php?id=" . $row["id_siswa"] . "'>Edit</a> | 
                            <a href='#' onclick='confirmHapus(" . $row["id_siswa"] . ")'>Hapus</a> |
                            <a href='tampilkan_presensi.php?id_siswa=" . $row["id_siswa"] . "'>Kehadiran</a> <!-- Button for attendance -->
                          </td>";
                    echo "</tr>";
                    $counter++; // Increment counter for the next row
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function confirmHapus(id) {
        if (confirm("Apakah Anda yakin ingin menghapus siswa ini?")) {
            window.location.href = "hapus_siswa.php?id=" + id;
        }
    }
</script>

</body>
</html>

<?php
// 5. Tutup koneksi database (penting!)
mysqli_close($conn);
?>