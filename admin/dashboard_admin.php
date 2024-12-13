<?php
session_start();

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin_username'])) {
    header("Location: login_admin.php");
    exit;
}

$username = $_SESSION['admin_username'];

// Include database connection file
include('../includes/db.php');

// Fetch total Poli
$query_poli = "SELECT COUNT(*) AS total_poli FROM poli1";
$result_poli = mysqli_query($conn, $query_poli);
$row_poli = mysqli_fetch_assoc($result_poli);
$total_poli = $row_poli['total_poli'];

// Fetch total active Dokter
$query_dokter = "SELECT COUNT(*) AS total_dokter FROM dokter3 WHERE status='aktif'";
$result_dokter = mysqli_query($conn, $query_dokter);
$row_dokter = mysqli_fetch_assoc($result_dokter);
$total_dokter = $row_dokter['total_dokter'];

// Fetch total registered Pasien
$query_pasien = "SELECT COUNT(*) AS total_pasien FROM pasien";
$result_pasien = mysqli_query($conn, $query_pasien);
$row_pasien = mysqli_fetch_assoc($result_pasien);
$total_pasien = $row_pasien['total_pasien'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar {
            width: 250px;
            background-color: #e8f0fe;
            height: 100vh;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .sidebar.hidden {
            transform: translateX(-100%);
        }
        .sidebar h2 {
            margin-bottom: 20px;
        }
        
        .sidebar a {
            display: block;
            color: #333;
            text-decoration: none;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .sidebar a:hover {
            text-decoration: underline;
        }
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-left: 250px;
            overflow-y: auto;
            transition: margin-left 0.3s ease-in-out;
        }
        .main.full {
            margin-left: 0;
        }
        .header {
            background-color: #4682B4;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
        }
        .toggle-sidebar {
            cursor: pointer;
            background: none;
            border: none;
            color: white;
            font-size: 20px;
        }
        .content {
            flex: 1;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .info-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .info-box h3 {
            margin-bottom: 10px;
        }
        .info-box p {
            font-size: 20px;
            font-weight: bold;
        }
        /* Add responsive canvas size */
        #chartPoli, #chartPasien {
            width: 100% !important;
            min-height: 300px;
        }
        @media (max-width: 768px) {
            #chartPoli, #chartPasien {
                min-height: 200px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h2><i class="fas fa-hospital"></i> Poli Klinik</h2>
        <a href="dashboard_admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="kelola_dokter.php"><i class="fas fa-user-md"></i> Dokter</a>
        <a href="kelola_pasien.php"><i class="fas fa-users"></i> Pasien</a>
        <a href="kelola_poli.php"><i class="fas fa-clinic-medical"></i> Poli</a>
        <a href="kelola_obat.php"><i class="fas fa-pills"></i> Obat</a>
        <a href="logout_admin.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main" id="main-content">
        <!-- Header -->
        <div class="header">
            <button class="toggle-sidebar" id="toggle-button">
                <i class="fas fa-bars"></i>
            </button>
            <h1>Dashboard Admin</h1>
            <div class="user-info">
                Selamat Datang, <strong><?php echo htmlspecialchars($username); ?></strong>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="row mb-4">
                <!-- Info Boxes -->
                <div class="col-md-4">
                    <div class="info-box">
                        <h3>Total Poli</h3>
                        <p><?php echo $total_poli; ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <h3>Total Dokter Aktif</h3>
                        <p><?php echo $total_dokter; ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <h3>Total Pasien</h3>
                        <p><?php echo $total_pasien; ?></p>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="row">
                <div class="col-md-6">
                    <canvas id="chartPoli"></canvas>
                </div>
                <div class="col-md-6">
                    <canvas id="chartPasien"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Chart.js configuration
        const ctx1 = document.getElementById('chartPoli').getContext('2d');
        const chartPoli = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Poli Umum', 'Poli Gigi', 'Poli Anak'],
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data: [12, 19, 3], // Example data
                    backgroundColor: ['#4682B4', '#5a9bd3', '#e8f0fe'],
                }]
            },
            options: {
                responsive: true,
            }
        });

        const ctx2 = document.getElementById('chartPasien').getContext('2d');
        const chartPasien = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['Januari', 'Februari', 'Maret'],
                datasets: [{
                    label: 'Jumlah Pendaftaran',
                    data: [10, 15, 20], // Example data
                    backgroundColor: 'rgba(70, 130, 180, 0.5)',
                    borderColor: '#4682B4',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
            }
        });

        // JavaScript for toggling sidebar
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const toggleButton = document.getElementById('toggle-button');

        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
            mainContent.classList.toggle('full');
            // Re-render charts after sidebar toggle
            chartPoli.update();
            chartPasien.update();
        });
    </script>
</body>
</html>
