<?php
session_start();
include('includes/db.php');

// Memeriksa apakah dokter sudah login
if (!isset($_SESSION['dokter_id'])) {
    header('Location: login_dokter.php');
    exit;
}

$dokter_id = $_SESSION['dokter_id'];
$query = "SELECT * FROM pasien WHERE jadwal_dokter_id = '$dokter_id' AND tanggal_periksa >= CURDATE()";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pasien</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="pasien-container">
        <h2>Daftar Pasien yang Akan Diperiksa</h2>
        
        <table>
            <tr>
                <th>Nama Pasien</th>
                <th>Tanggal Pemeriksaan</th>
                <th>Waktu Pemeriksaan</th>
                <th>Aksi</th>
            </tr>
            <?php while ($pasien = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $pasien['nama_pasien']; ?></td>
                    <td><?php echo $pasien['tanggal_periksa']; ?></td>
                    <td><?php echo $pasien['waktu_periksa']; ?></td>
                    <td><a href="catatan_obat.php?pasien_id=<?php echo $pasien['id']; ?>">Catatan Obat</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>
