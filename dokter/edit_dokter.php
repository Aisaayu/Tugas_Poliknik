<?php
session_start();
include('includes/db.php');

// Memeriksa apakah dokter sudah login
if (!isset($_SESSION['dokter_id'])) {
    header('Location: login_dokter.php');
    exit;
}

// Ambil data dokter yang login
$dokter_id = $_SESSION['dokter_id'];
$query = "SELECT * FROM dokter WHERE id = '$dokter_id'";
$result = mysqli_query($conn, $query);
$dokter = mysqli_fetch_assoc($result);

// Memperbarui data dokter
if (isset($_POST['update_dokter'])) {
    $nama_dokter = $_POST['nama_dokter'];
    $gelar = $_POST['gelar'];
    $email = $_POST['email'];

    $sql = "UPDATE dokter SET nama_dokter = '$nama_dokter', gelar = '$gelar', email = '$email' WHERE id = '$dokter_id'";
    if (mysqli_query($conn, $sql)) {
        echo "Data dokter berhasil diperbarui!";
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
    <title>Edit Data Dokter</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="edit-dokter-container">
        <h2>Edit Data Dokter</h2>
        
        <!-- Form Edit Data Dokter -->
        <form method="POST">
            <label for="nama_dokter">Nama Dokter</label>
            <input type="text" name="nama_dokter" value="<?php echo $dokter['nama_dokter']; ?>" required>
            
            <label for="gelar">Gelar</label>
            <input type="text" name="gelar" value="<?php echo $dokter['gelar']; ?>" required>
            
            <label for="email">Email</label>
            <input type="email" name="email" value="<?php echo $dokter['email']; ?>" required>
            
            <button type="submit" name="update_dokter">Perbarui Data</button>
        </form>
    </div>

</body>
</html>
