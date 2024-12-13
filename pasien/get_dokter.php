<?php
include('../includes/db.php');

// Periksa apakah ada parameter poli_id
if (isset($_GET['poli_id'])) {
    $poli_id = $_GET['poli_id'];

    // Ambil dokter berdasarkan poli
    $dokter_query = "SELECT * FROM dokter3 WHERE id_poli = ?";
    $stmt = mysqli_prepare($conn, $dokter_query);
    mysqli_stmt_bind_param($stmt, 'i', $poli_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $dokter_list = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $dokter_list[] = $row;
    }

    // Kirimkan response dalam format JSON
    echo json_encode($dokter_list);
}
?>
