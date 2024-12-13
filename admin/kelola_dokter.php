<?php
session_start();
require_once '../includes/db.php'; // Pastikan file database ada untuk koneksi

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin_username'])) {
    header("Location: login_admin.php");
    exit;
}

// Ambil semua data dokter dan poli
$query_dokter = "SELECT d.*, p.nama_poli FROM dokter3 d 
                 LEFT JOIN poli1 p ON d.id_poli = p.id_poli";
$result_dokter = mysqli_query($conn, $query_dokter);

$query_poli = "SELECT * FROM poli1";
$result_poli = mysqli_query($conn, $query_poli);

// Tambah data dokter
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_dokter'])) {
    $nama_dokter = $_POST['nama_dokter'];
    $spesialis = $_POST['spesialis'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];
    $id_poli = $_POST['id_poli'];
    $status = $_POST['status'];

    $insert_query = "INSERT INTO dokter3 (nama_dokter, spesialis, no_hp, email, id_poli, status)
                     VALUES ('$nama_dokter', '$spesialis', '$no_hp', '$email', '$id_poli', '$status')";
    mysqli_query($conn, $insert_query);
    header("Location: kelola_dokter.php");
    exit;
}

// Hapus data dokter
if (isset($_GET['delete'])) {
    $id_dokter = $_GET['delete'];
    $delete_query = "DELETE FROM dokter3 WHERE id_dokter = $id_dokter";
    mysqli_query($conn, $delete_query);
    header("Location: kelola_dokter.php");
    exit;
}

// Update data dokter
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_dokter'])) {
    $id_dokter = $_POST['id_dokter'];
    $nama_dokter = $_POST['nama_dokter'];
    $spesialis = $_POST['spesialis'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];
    $status = $_POST['status'];

    $update_query = "UPDATE dokter3 SET 
                     nama_dokter = '$nama_dokter',
                     spesialis = '$spesialis',
                     no_hp = '$no_hp',
                     email = '$email',
                     status = '$status'
                     WHERE id_dokter = $id_dokter";
    mysqli_query($conn, $update_query);
    header("Location: kelola_dokter.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Dokter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .table th, .table td {
            text-align: center;
        }
        .table th {
            background-color: #4682B4;
            color: white;
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
            <h1>Kelola Data Dokter</h1>
            <div class="user-info">
                Selamat Datang, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="container">
                <!-- Button untuk membuka modal -->
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addDoctorModal">Tambah Dokter</button>

                
                <table class="table table-bordered table-striped table-hover">
                <thead>
                        <tr>
                            <th>Nama Dokter</th>
                            <th>Spesialis</th>
                            <th>No HP</th>
                            <th>Email</th>
                            <th>Poli</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result_dokter)) { ?>
                            <tr>
                                <td><?= $row['nama_dokter'] ?></td>
                                <td><?= $row['spesialis'] ?></td>
                                <td><?= $row['no_hp'] ?></td>
                                <td><?= $row['email'] ?></td>
                                <td><?= $row['nama_poli'] ?></td>
                                <td><?= ucfirst($row['status']) ?></td>
                                <td>
    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editDoctorModal" onclick="editDoctor(<?= $row['id_dokter'] ?>)">
        <i class="fas fa-edit"></i> Edit
    </button>
    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $row['id_dokter'] ?>)">
        <i class="fas fa-trash"></i> Hapus
    </button>
</td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Dokter -->
    <div class="modal fade" id="addDoctorModal" tabindex="-1" aria-labelledby="addDoctorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDoctorModalLabel">Tambah Dokter Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="kelola_dokter.php" method="POST">
                        <div class="mb-3">
                            <label for="nama_dokter" class="form-label">Nama Dokter</label>
                            <input type="text" class="form-control" id="nama_dokter" name="nama_dokter" required>
                        </div>
                        <div class="mb-3">
                            <label for="spesialis" class="form-label">Spesialis</label>
                            <input type="text" class="form-control" id="spesialis" name="spesialis" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_poli" class="form-label">Poli</label>
                            <select class="form-control" id="id_poli" name="id_poli" required>
                                <?php while ($poli = mysqli_fetch_assoc($result_poli)) { ?>
                                    <option value="<?= $poli['id_poli'] ?>"><?= $poli['nama_poli'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="aktif">Aktif</option>
                                <option value="non-aktif">Non-Aktif</option>
                            </select>
                        </div>
                        <button type="submit" name="add_dokter" class="btn btn-primary">Simpan Dokter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Edit Dokter -->
<div class="modal fade" id="editDoctorModal" tabindex="-1" aria-labelledby="editDoctorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDoctorModalLabel">Edit Dokter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="kelola_dokter.php" method="POST">
                    <input type="hidden" id="edit_id_dokter" name="id_dokter">
                    <div class="mb-3">
                        <label for="edit_nama_dokter" class="form-label">Nama Dokter</label>
                        <input type="text" class="form-control" id="edit_nama_dokter" name="nama_dokter" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_spesialis" class="form-label">Spesialis</label>
                        <input type="text" class="form-control" id="edit_spesialis" name="spesialis" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_no_hp" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="edit_no_hp" name="no_hp" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="non-aktif">Non-Aktif</option>
                        </select>
                    </div>
                    <button type="submit" name="edit_dokter" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi untuk konfirmasi hapus dokter
        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus dokter ini?',
                text: 'Data dokter ini akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'kelola_dokter.php?delete=' + id;
                }
            });
        }

        // Fungsi untuk membuka modal edit dokter
        function editDoctor(id) {
    // Mengambil data dokter berdasarkan id menggunakan AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_dokter.php?id=' + id, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const dokter = JSON.parse(xhr.responseText);

            // Isi form dengan data dokter
            document.getElementById('edit_id_dokter').value = dokter.id_dokter;
            document.getElementById('edit_nama_dokter').value = dokter.nama_dokter;
            document.getElementById('edit_spesialis').value = dokter.spesialis;
            document.getElementById('edit_no_hp').value = dokter.no_hp;
            document.getElementById('edit_email').value = dokter.email;
            
            // Set poli yang dipilih
            document.getElementById('edit_id_poli').value = dokter.id_poli;

            // Set status
            document.getElementById('edit_status').value = dokter.status;
        }
    };
    xhr.send();
}




        // Menambahkan event listener untuk tombol toggle sidebar
        document.getElementById('toggle-button').addEventListener('click', function() {
            var sidebar = document.getElementById('sidebar');
            var mainContent = document.getElementById('main-content');

            // Toggle class 'hidden' pada sidebar
            sidebar.classList.toggle('hidden');

            // Toggle margin-left pada main content
            mainContent.classList.toggle('full');
        });
    </script>
</body>
</html>
