<?php
session_start();
include('includes/db.php');

// Memeriksa apakah dokter sudah login
if (!isset($_SESSION['dokter_id'])) {
    header('Location: login_dokter.php');
    exit;
}

if (isset($_POST['submit_jadwal'])) {
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $dokter_id = $_SESSION['dokter_id'];

    $sql = "INSERT INTO jadwal (dokter_id, tanggal, waktu) VALUES ('$dokter_id', '$tanggal', '$waktu')";
    if (mysqli_query($conn, $sql)) {
        echo "Jadwal berhasil ditambahkan!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Jadwal Pemeriksaan</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="jadwal-container">
        <h2>Atur Jadwal Pemeriksaan</h2>
        
        <!-- Form Atur Jadwal -->
        <form method="POST">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" required>
            
            <label for="waktu">Waktu</label>
            <input type="time" name="waktu" required>
            
            <button type="submit" name="submit_jadwal">Atur Jadwal</button>
        </form>
    </div>

</body>
</html>
