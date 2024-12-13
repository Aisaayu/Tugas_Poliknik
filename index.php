<?php
session_start(); // Memulai sesi
include('includes/db.php'); // Memasukkan file koneksi database

// Pastikan user_role didefinisikan
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poli Klinik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen">
    <h1 class="text-3xl font-bold text-center mb-6">SELAMAT DATANG DI POLI KLINIK</h1>
    <div class="flex justify-center mb-6">
        <img src="https://storage.googleapis.com/a1aa/image/2LMtPVE1iI7wC5EODWX0INoFFTAWQf1dg4xL6hUvk1ULwE8JA.jpg" alt="Ilustrasi staf medis dan pasien di kursi roda" class="w-64 h-auto">
    </div>

    <!-- Flexbox untuk tiga kotak login di kiri, tengah, dan kanan -->
    <div class="flex justify-between w-full px-10 gap-6 mb-6">
        <!-- Kotak Kiri -->
        <div class="bg-white p-6 rounded-lg shadow-lg text-center w-1/3 mt-4">
            <h2 class="text-xl font-bold mb-4">Silakan login untuk melanjutkan Admin</h2>
            <p>Jika Anda seorang admin, silakan masuk untuk melanjutkan</p>
            <form action="admin/login_admin.php" method="get">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg mt-3 text-lg font-semibold hover:bg-blue-600">
                    Masuk
                </button>
            </form>
        </div>

        <!-- Kotak Tengah -->
        <div class="bg-white p-6 rounded-lg shadow-lg text-center w-1/3 mt-4">
            <h2 class="text-xl font-bold mb-4">Silakan login untuk melanjutkan Dokter</h2>
            <p>Jika Anda seorang dokter, silakan masuk untuk melanjutkan</p>
            <form action="dokter/login_dokter.php" method="get">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg mt-3 text-lg font-semibold hover:bg-blue-600">
                    Masuk
                </button>
            </form>
        </div>

        <!-- Kotak Kanan -->
        <div class="bg-white p-6 rounded-lg shadow-lg text-center w-1/3 mt-4">
            <h2 class="text-xl font-bold mb-4">Silakan login untuk melanjutkan Pasien</h2>
            <p>Jika Anda seorang pasien, silakan masuk untuk melanjutkan</p>
            <form action="pasien/login_pasien.php" method="get">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg mt-3 text-lg font-semibold hover:bg-blue-600">
                    Masuk
                </button>
            </form>
        </div>
    </div>

    <div class="container mt-5">
        <?php if ($user_role == 'admin'): ?>
            <!-- Dashboard Admin -->
            <div class="dashboard">
                <h2 class="text-center text-primary">Dashboard Admin</h2>
                <div class="row mt-4 text-center">
                    <div class="col-md-4">
                        <div class="card shadow-lg bg-light">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-user-md fa-2x text-success"></i></h5>
                                <p class="card-text">Jumlah Dokter</p>
                                <?php
                                $query = "SELECT COUNT(*) as total_dokter FROM dokter";
                                $result = mysqli_query($conn, $query);
                                $row = mysqli_fetch_assoc($result);
                                echo "<h3>" . $row['total_dokter'] . "</h3>";
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-lg bg-light">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-users fa-2x text-info"></i></h5>
                                <p class="card-text">Jumlah Pasien</p>
                                <?php
                                $query = "SELECT COUNT(*) as total_pasien FROM pasien";
                                $result = mysqli_query($conn, $query);
                                $row = mysqli_fetch_assoc($result);
                                echo "<h3>" . $row['total_pasien'] . "</h3>";
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-lg bg-light">
                        <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-clinic-medical fa-2x text-danger"></i></h5>
                                <p class="card-text">Jumlah Poli</p>
                                <?php
                                $query = "SELECT COUNT(*) as total_poli FROM poli";
                                $result = mysqli_query($conn, $query);
                                $row = mysqli_fetch_assoc($result);
                                echo "<h3>" . $row['total_poli'] . "</h3>";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif ($user_role == 'dokter'): ?>
            <!-- Dashboard Dokter -->
            <div class="dashboard mt-4">
                <?php if (isset($_SESSION['dokter_nama']) && isset($_SESSION['dokter_id'])): ?>
                    <p class="text-center">Selamat datang, <strong>Dr. <?php echo $_SESSION['dokter_nama']; ?></strong></p>
                    <div class="mt-3">
                        <h5 class="text-info">Jadwal Periksa Hari Ini:</h5>
                        <?php
                        $dokter_id = $_SESSION['dokter_id'];
                        $query = "SELECT * FROM jadwal WHERE dokter_id = '$dokter_id' AND tanggal = CURDATE()";
                        $result = mysqli_query($conn, $query);
                        if (mysqli_num_rows($result) > 0) {
                            echo "<ul class='list-group'>";
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<li class='list-group-item'>" . $row['waktu'] . " - " . $row['pasien_nama'] . "</li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p class='text-muted'>Belum ada pasien yang dijadwalkan untuk hari ini.</p>";
                        }
                        ?>
                    </div>
                <?php else: ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include('includes/footer.php'); // Menyertakan footer ?>
</body>
</html>
