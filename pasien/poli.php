<?php
session_start();

if (!isset($_SESSION['pasien_id'])) {
    header("Location: login_pasien.php");
    exit;
}

$pasien_id = $_SESSION['pasien_id'];

$query = "SELECT * FROM poli";
$result = mysqli_query($conn, $query);
$poli_list = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (isset($_POST['submit'])) {
    $poli_id = $_POST['poli_id'];
    $dokter_id = $_POST['dokter_id'];
    $tanggal_periksa = $_POST['tanggal_periksa'];
    $waktu_periksa = $_POST['waktu_periksa'];

    $stmt = mysqli_prepare($conn, "INSERT INTO pendaftaran (pasien_id, poli_id, dokter_id, tanggal_periksa, waktu_periksa) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iiiss", $pasien_id, $poli_id, $dokter_id, $tanggal_periksa, $waktu_periksa);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "Pendaftaran ke poli berhasil!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Poli</title>
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
            margin-left: 250px; /* Add space to the left to avoid overlap with sidebar */
            overflow-y: auto;
        }
        .header {
            background-color: #4682B4;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-left: 50px;
        }
        .header h1 {
            margin: 0;
        }
        .content {
            flex: 1;
            padding: 20px;
            background-color: #f5f5f5;
            overflow-y: auto;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .form-container input, .form-container select, .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container button {
            padding: 10px 15px;
            background-color: #4682B4;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #5a9bd3;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Menu</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="pendaftaran_pasien_baru.php">Pendaftaran Pasien Baru</a>
        <a href="pendaftaran_poli.php">Pendaftaran Poli</a>
        <a href="profil_pasien.php">Profil Pasien</a>
        <a href="logout_pasien.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <!-- Header -->
        <div class="header">
            <h1>Pendaftaran Poli</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Formulir -->
            <div class="form-container">
                <h2>Formulir Pendaftaran Poli</h2>
                <form method="POST">
                    <label for="poli_id">Pilih Poli:</label>
                    <select name="poli_id" required>
                        <?php foreach ($poli_list as $poli) { ?>
                            <option value="<?php echo $poli['id']; ?>"><?php echo $poli['nama_poli']; ?></option>
                        <?php } ?>
                    </select>

                    <label for="dokter_id">Pilih Dokter:</label>
                    <input type="text" name="dokter_id" required>

                    <label for="tanggal_periksa">Tanggal Pemeriksaan:</label>
                    <input type="date" name="tanggal_periksa" required>

                    <label for="waktu_periksa">Waktu Pemeriksaan:</label>
                    <input type="time" name="waktu_periksa" required>

                    <button type="submit" name="submit">Daftar</button>
                    <button type="reset">Reset</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
