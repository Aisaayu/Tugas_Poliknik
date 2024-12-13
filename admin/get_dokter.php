<?php
require_once '../includes/db.php'; // Pastikan koneksi database benar

// Cek koneksi database
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Periksa apakah ada parameter 'id' pada URL
if (isset($_GET['id'])) {
    $id_dokter = $_GET['id'];

    // Cek apakah id_dokter valid
    if (!is_numeric($id_dokter)) {
        echo json_encode(['error' => 'ID dokter tidak valid']);
        exit;
    }

    // Query untuk mengambil data dokter berdasarkan id_dokter
    $query = "SELECT * FROM dokter3 WHERE id_dokter = $id_dokter";
    $result = mysqli_query($conn, $query);

    // Cek apakah query berhasil dan ada hasilnya
    if ($result) {
        if ($row = mysqli_fetch_assoc($result)) {
            echo json_encode($row); // Kembalikan data dokter dalam format JSON
        } else {
            echo json_encode(['error' => 'Dokter tidak ditemukan']);
        }
    } else {
        // Jika query gagal
        echo json_encode(['error' => 'Query gagal: ' . mysqli_error($conn)]);
    }
} else {
    // Jika parameter 'id' tidak ditemukan
    echo json_encode(['error' => 'ID dokter tidak ditemukan']);
}
?>
