<?php
session_start();
include('../includes/db.php'); // Koneksi ke database

// Proses pendaftaran admin
if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validasi: Cek apakah password dan konfirmasi password cocok
    if ($password !== $confirm_password) {
        $error_message = "Password dan konfirmasi password tidak cocok!";
    } else {
        // Hash password untuk penyimpanan yang aman
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query untuk mengecek apakah username sudah ada
        $query = "SELECT * FROM admin WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        // Jika username sudah terdaftar
        if (mysqli_num_rows($result) > 0) {
            $error_message = "Username sudah digunakan!";
        } else {
            // Query untuk menambahkan admin baru
            $query = "INSERT INTO admin (username, password) VALUES ('$username', '$hashed_password')";
            if (mysqli_query($conn, $query)) {
                $_SESSION['admin_username'] = $username;
                header("Location: login_admin.php"); // Redirect ke login
                exit();
            } else {
                $error_message = "Pendaftaran gagal, coba lagi!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full">
        <h2 class="text-2xl font-bold text-center mb-6">Register Admin</h2>

        <!-- Menampilkan pesan error jika ada -->
        <?php if (isset($error_message)): ?>
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="register_admin.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-semibold">Username</label>
                <input type="text" name="username" id="username" class="w-full p-3 mt-2 border border-gray-300 rounded" required>
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-semibold">Password</label>
                <input type="password" name="password" id="password" class="w-full p-3 mt-2 border border-gray-300 rounded" required>
            </div>

            <div class="mb-6">
                <label for="confirm_password" class="block text-sm font-semibold">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="w-full p-3 mt-2 border border-gray-300 rounded" required>
            </div>
            
            <button type="submit" name="register" class="w-full bg-blue-500 text-white p-3 rounded-lg font-semibold hover:bg-blue-600">Register</button>
        </form>

        <div class="mt-4 text-center">
            <a href="login_admin.php" class="text-blue-500 hover:text-blue-700 font-semibold">Sudah punya akun? Login di sini</a>
        </div>
    </div>
</body>
</html>
