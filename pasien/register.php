<?php
// Menghubungkan ke database (sesuaikan dengan konfigurasi database Anda)
include('../includes/db.php');

// Menangani proses registrasi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);

    // Validasi password
    if ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok!";
    } else {
        // Mengecek apakah username sudah terdaftar
        $check_username = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $check_username);

        if (mysqli_num_rows($result) > 0) {
            $error = "Username sudah digunakan!";
        } else {
            // Query untuk menambahkan pengguna baru ke database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'pasien'; // Mengatur role sebagai pasien

            $query = "INSERT INTO users (nama, username, password, email, no_telp, role) 
                      VALUES ('$nama', '$username', '$hashed_password', '$email', '$no_telp', '$role')";
            if (mysqli_query($conn, $query)) {
                // Redirect ke halaman login setelah berhasil registrasi
                header("Location: ../pasien/login_pasien.php");
                exit();
            } else {
                $error = "Terjadi kesalahan saat registrasi, coba lagi!";
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
    <title>Register - Poli Klinik</title>
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
        <h2 class="text-2xl font-bold text-center mb-6">Daftar Pasien Baru</h2>
        
        <!-- Menampilkan pesan error jika registrasi gagal -->
        <?php if (isset($error)): ?>
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form action="register.php" method="POST">
            <div class="mb-4">
                <label for="nama" class="block text-sm font-semibold">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" class="w-full p-3 mt-2 border border-gray-300 rounded" required>
            </div>
            
            <div class="mb-4">
                <label for="username" class="block text-sm font-semibold">Username</label>
                <input type="text" name="username" id="username" class="w-full p-3 mt-2 border border-gray-300 rounded" required>
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-semibold">Password</label>
                <input type="password" name="password" id="password" class="w-full p-3 mt-2 border border-gray-300 rounded" required>
            </div>
            
            <div class="mb-4">
                <label for="confirm_password" class="block text-sm font-semibold">Konfirmasi Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="w-full p-3 mt-2 border border-gray-300 rounded" required>
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-sm font-semibold">Email</label>
                <input type="email" name="email" id="email" class="w-full p-3 mt-2 border border-gray-300 rounded" required>
            </div>
            
            <div class="mb-4">
                <label for="no_telp" class="block text-sm font-semibold">Nomor Telepon</label>
                <input type="text" name="no_telp" id="no_telp" class="w-full p-3 mt-2 border border-gray-300 rounded" required>
            </div>
            
            <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded-lg font-semibold hover:bg-blue-600">Daftar</button>
        </form>

        <div class="mt-4 text-center">
            <a href="login_pasien.php" class="text-blue-500 hover:text-blue-700 font-semibold">Sudah punya akun? Login di sini</a>
        </div>
    </div>
</body>
</html>
