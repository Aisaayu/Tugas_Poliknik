<?php
session_start();

// Menghapus sesi
session_unset();
session_destroy();

// Mengarahkan ke halaman utama (index.php)
header('Location: ../index.php');
exit;
?>
