<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Koneksi ke database
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'poliklinik';

    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query untuk menghapus data poli
    $sql = "DELETE FROM poli1 WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Data poli berhasil dihapus.";
        header("Location: kelola_poli.php"); // Kembali ke halaman kelola poli
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
