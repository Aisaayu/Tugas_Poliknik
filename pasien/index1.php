<?php 
session_start(); 
include '../includes/db.php';

// Menangani proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE username = '$username' AND role = 'pasien'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['id_pasien'] = $row['id'];
            $_SESSION['nama_pasien'] = $row['username'];
            $_SESSION['alamat_pasien'] = $row['alamat_pasien'];
            $_SESSION['no_ktp'] = $row['no_ktp'];
            $_SESSION['no_hp'] = $row['no_hp'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Login Pasien</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login Pasien</h2>
        <form action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
            <?php if (isset($error)): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>