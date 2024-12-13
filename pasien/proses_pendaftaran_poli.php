<?php
session_start();
include 'includes/db.php';

$poli_id = $_POST['poli_id'];
$dokter_id = $_POST['dokter_id'];
$pasien_id = $_SESSION['id_pasien'];

$query = "INSERT INTO pendaftaran_poli (pasien_id, poli_id, dokter_id) VALUES ('$pasien_id', '$poli_id', '$dokter_id')";
mysqli_query($conn, $query);

header('Location: dashboard.php');
exit;
?>