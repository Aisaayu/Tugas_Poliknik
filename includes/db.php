<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "poliklinik";

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);
$conn->set_charset("utf8mb4");

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
