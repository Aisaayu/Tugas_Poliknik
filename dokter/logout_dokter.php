<?php
session_start();

// Menghapus semua data sesi
session_unset();
session_destroy();

// Mengarahkan ke halaman utama (index.php)
header('Location: ../index.php');
exit;
?>
