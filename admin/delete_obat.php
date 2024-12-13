<?php
include('includes/db.php');

// Mendapatkan ID obat yang akan dihapus
$id = $_GET['id'];

// Menghapus data obat berdasarkan ID
$query = "DELETE FROM obat WHERE id = '$id'";
if (mysqli_query($conn, $query)) {
    echo "Obat berhasil dihapus!";
    header("Location: manage_obat.php"); // Kembali ke halaman kelola obat setelah dihapus
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
