<?php
session_start();
include('includes/db.php');
include('templates/header.html');  // Menyertakan header
include('templates/navbar.html');  // Menyertakan navbar

// Memeriksa apakah dokter sudah login
if (!isset($_SESSION['dokter_id'])) {
    header('Location: login_dokter.php');
    exit;
}

// Mengambil ID Dokter yang login
$dokter_id = $_SESSION['dokter_id'];

// Mengambil riwayat pemeriksaan untuk pasien yang diperiksa oleh Dokter tersebut
$query = "SELECT p.nama_pasien, riwayat.tanggal_periksa, riwayat.hasil_periksa, riwayat.obat, riwayat.biaya
          FROM riwayat
          JOIN pasien p ON riwayat.pasien_id = p.id
          WHERE riwayat.dokter_id = '$dokter_id'
          ORDER BY riwayat.tanggal_periksa DESC";

$result = mysqli_query($conn, $query);
?>

<div class="riwayat-container">
    <h2>Riwayat Pemeriksaan Pasien</h2>

    <?php if (mysqli_num_rows($result) > 0) { ?>
        <table>
            <tr>
                <th>Nama Pasien</th>
                <th>Tanggal Pemeriksaan</th>
                <th>Hasil Pemeriksaan</th>
                <th>Obat</th>
                <th>Biaya</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['nama_pasien']; ?></td>
                    <td><?php echo $row['tanggal_periksa']; ?></td>
                    <td><?php echo $row['hasil_periksa']; ?></td>
                    <td><?php echo $row['obat']; ?></td>
                    <td><?php echo $row['biaya']; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
        <p>Tidak ada riwayat pemeriksaan pasien yang ditemukan.</p>
    <?php } ?>
</div>

<?php include('templates/footer.html'); ?>  <!-- Menyertakan footer -->
