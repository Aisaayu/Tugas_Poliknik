<?php
session_start(); // Memulai sesi

// Menghubungkan ke database (sesuaikan dengan konfigurasi database Anda)
include('includes/db.php');

// Menangani proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query untuk mengecek apakah username dan password ada dalam database
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Jika ditemukan, ambil data pengguna
        $row = mysqli_fetch_assoc($result);
        
        // Menyimpan informasi pengguna dalam sesi
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_role'] = $row['role'];
        
        // Mengarahkan ke halaman sesuai peran
        if ($row['role'] == 'admin') {
            header("Location: dashboard_admin.php");
        } elseif ($row['role'] == 'dokter') {
            header("Location: dashboard_dokter.php");
        } elseif ($row['role'] == 'pasien') {
            header("Location: dashboard_pasien.php");
        }
    } else {
        // Jika username atau password salah
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Poli Klinik</title>
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
        <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
        
        <!-- Menampilkan pesan error jika login gagal -->
        <?php if (isset($error)): ?>
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-semibold">Username</label>
                <input type="text" name="username" id="username" class="w-full p-3 mt-2 border border-gray-300 rounded" required>
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold">Password</label>
                <input type="password" name="password" id="password" class="w-full p-3 mt-2 border border-gray-300 rounded" required>
            </div>
            
            <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-lg font-semibold hover:bg-blue-600">Login</button>
        </form>
    </div>
</body>
</html>
