<?php
// 1. Panggil file koneksi database untuk presensi
include 'config.php';

// 2. Query untuk mengambil data presensi
$sql = "SELECT p.id_presensi, p.tanggal, p.status, p.keterangan, s.nama_siswa, s.kelas 
        FROM presensi p
        JOIN siswa s ON p.id_siswa = s.id_siswa
        ORDER BY p.tanggal DESC"; // Sorting by most recent date
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
    <title>Daftar Kehadiran</title>
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
        }

        h2 {
            text-align: center;
            color: #ffcc00;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #444;
        }

        th {
            background-color: #333;
            color: #ffcc00;
        }

        td {
            background-color: #222;
        }

        .back-button {
            display: block;
            text-align: center;
            margin-top: 30px;
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
    <h2>Daftar Kehadiran</h2>

    <table>
        <thead>
            <tr>
                <th>ID Presensi</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Menampilkan data presensi dari database
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["id_presensi"] . "</td>";
                echo "<td>" . $row["nama_siswa"] . "</td>";
                echo "<td>" . $row["kelas"] . "</td>";
                echo "<td>" . $row["tanggal"] . "</td>";
                echo "<td>" . $row["status"] . "</td>";
                echo "<td>" . $row["keterangan"] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="back-button">
        <a href="tampilkan_siswa.php">Kembali ke Beranda</a>
    </div>

</div>

</body>
</html>

<?php
// 4. Tutup koneksi database
mysqli_close($conn);
?>
