<?php
session_start();
include('../includes/db.php'); // Memastikan koneksi ke database terhubung

// Menangani proses login
if (isset($_POST['login'])) {
    // Mengamankan input dari user untuk mencegah SQL Injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query untuk memeriksa apakah username ada dalam database
    $query = "SELECT * FROM admin WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    // Cek apakah query berhasil dan data admin ditemukan
    if ($result && mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);

        // Verifikasi password yang diinputkan dengan password yang disimpan di database
        if (password_verify($password, $admin['password'])) {
            // Jika password cocok, simpan data admin dalam session
            $_SESSION['admin_username'] = $admin['username'];
            // Redirect ke dashboard admin
            header("Location: dashboard_admin.php");
            exit();
        } else {
            $error_message = "Username atau Password salah!";
        }
    } else {
        $error_message = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
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
        <h2 class="text-2xl font-bold text-center mb-6">Login Admin</h2>

        <!-- Menampilkan pesan error jika login gagal -->
        <?php if (isset($error_message)): ?>
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="login_admin.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-semibold">Username</label>
                <input type="text" name="username" id="username" class="w-full p-3 mt-2 border border-gray-300 rounded" required>
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold">Password</label>
                <input type="password" name="password" id="password" class="w-full p-3 mt-2 border border-gray-300 rounded" required>
            </div>
            
            <button type="submit" name="login" class="w-full bg-blue-500 text-white p-3 rounded-lg font-semibold hover:bg-blue-600">Login</button>
        </form>

    </div>
</body>
</html>
