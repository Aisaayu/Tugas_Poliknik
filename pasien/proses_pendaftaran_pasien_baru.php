<?php
session_start();
include 'includes/db.php';

$nama_pasien = $_POST['nama_pasien'];
$alamat_pasien = $_POST['alamat_pasien'];
$no_ktp = $_POST['no_ktp'];
$no_hp = $_POST['no_hp'];

$query = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp) VALUES ('$nama_pasien', '$alamat_pasien', '$no_ktp', '$no_hp')";
mysqli_query($conn, $query);

header('Location: dashboard.php');
exit;
?>