<?php 
session_start(); 
include '../includes/db.php';

if (!isset($_SESSION['id_pasien'])) { 
    header('Location:../pasien/index1.php'); 
    exit; 
}

$_SESSION['alamat_pasien'] = $_SESSION['alamat_pasien'] ?? '';
$_SESSION['no_ktp'] = $_SESSION['no_ktp'] ?? '';
$_SESSION['no_hp'] = $_SESSION['no_hp'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil Pasien</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-100 p-4">
            <h2 class="text-lg font-bold">Menu</h2>
            <ul class="list-none">
                <li><a href="dashboard.php">Dashboard Pasien</a></li>
                <li><a href="pendaftaran_pasien_baru.php">Pendaftaran Pasien Baru</a></li>
                <li><a href="pendaftaran_poli.php">Pendaftaran Poli</a></li>
                <li><a href="#">Profil Pasien</a></li>
            </ul>
        </div>
        <!-- Konten -->
        <div class="flex-1 p-4">
            <h2 class="text-lg font-bold">Profil Pasien</h2>
            <p>Nama: <?= $_SESSION['nama_pasien'] ?></p>
            <p>Alamat: <?= $_SESSION['alamat_pasien'] ?></p>
            <p>No. KTP: <?= $_SESSION['no_ktp'] ?></p>
            <p>No. HP: <?= $_SESSION['no_hp'] ?></p>
        </div>
    </div>
</body>
</html>