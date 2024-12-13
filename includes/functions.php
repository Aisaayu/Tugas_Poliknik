<?php
function getProfilPasien($id_pasien) {
    include 'db.php';
    $query = "SELECT * FROM pasien WHERE id = '$id_pasien'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
   }
// Fungsi untuk memvalidasi input form
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk mengecek apakah pengguna sudah login
function checkLogin($user_role) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    if ($_SESSION['user_role'] != $user_role) {
        header("Location: unauthorized.php");
        exit;
    }
}



?>
