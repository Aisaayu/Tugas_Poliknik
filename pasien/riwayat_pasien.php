<?php
session_start();
include('includes/db.php');

// Memeriksa apakah pasien sudah login
if (!isset($_SESSION['pasien_id'])) {
    header('Location: login_pasien.php');
    exit;
}

$pasien_id = $_SESSION['pasien_id'];
$query = "SELECT p.nama_pasien, riwayat.tanggal_periksa, riwayat.hasil_periksa, riwayat.obat, riwayat.biaya
          FROM riwayat
          JOIN pasien p ON riwayat.pasien_id = p.id
          WHERE riwayat.pasien_id = '$pasien_id'
          ORDER BY riwayat.tanggal_periksa DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemeriksaan</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="riwayat-container">
        <h2>Riwayat Pemeriksaan Pasien</h2>

        <!-- Tabel Riwayat Pemeriksaan -->
        <table>
            <tr>
                <th>Tanggal Pemeriksaan</th>
                <th>Hasil Pemeriksaan</th>
                <th>Obat</th>
                <th>Biaya</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['tanggal_periksa']; ?></td>
                    <td><?php echo $row['hasil_periksa']; ?></td>
                    <td><?php echo $row['obat']; ?></td>
                    <td><?php echo $row['biaya']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>
