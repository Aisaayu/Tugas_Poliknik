<?php
session_start(); // Mulai sesi untuk autentikasi admin
include '../includes/db.php'; // File koneksi database

// Proses Tambah Obat
if (isset($_POST['tambah_obat'])) {
    $nama_obat = $_POST['nama_obat'];
    $harga_obat = $_POST['harga_obat'];

    $sql = "INSERT INTO obat (nama_obat, harga) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
$stmt->bind_param("si", $nama_obat, $harga_obat);


    if ($stmt->execute()) {
       // $_SESSION['message'] = 'Data obat berhasil ditambahkan!';
    } else {
       // $_SESSION['message'] = 'Gagal menambahkan data obat.';
    }
    header('Location: kelola_obat.php');
    exit;
}

if (isset($_POST['ubah_obat'])) {
    $id_obat = $_POST['id_obat'];
    $nama_obat = $_POST['nama_obat'];
    $harga_obat = $_POST['harga_obat'];

    $sql = "UPDATE obat SET nama_obat = ?, harga = ? WHERE id_obat = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $nama_obat, $harga_obat, $id_obat);

    if ($stmt->execute()) {
       // $_SESSION['message'] = 'Data obat berhasil diubah!';
    } else {
       // $_SESSION['message'] = 'Gagal mengubah data obat.';
    }
    header('Location: kelola_obat.php');
    exit;
}
if (isset($_POST['hapus'])) {
    $id_obat = $_POST['hapus']; // Mengambil nilai dari parameter 'hapus' yang dikirim dari JavaScript

    $sql = "DELETE FROM obat WHERE id_obat = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_obat);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
    exit;
}



// Ambil Data Obat dari Database
$sql = "SELECT id_obat, nama_obat, harga, deskripsi FROM obat";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Obat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
        /* Menambahkan margin di sebelah kanan tombol untuk memberi jarak */
.btn-spacing {
    margin-right: 10px;
}

    </style>
</head>
<body>
<div class="sidebar" id="sidebar">
    <h2><i class="fas fa-hospital"></i> Poli Klinik</h2>
    <a href="dashboard_admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="kelola_dokter.php"><i class="fas fa-user-md"></i> Dokter</a>
    <a href="kelola_pasien.php"><i class="fas fa-users"></i> Pasien</a>
    <a href="kelola_poli.php"><i class="fas fa-clinic-medical"></i> Poli</a>
    <a href="kelola_obat.php"><i class="fas fa-pills"></i> Obat</a>
    <a href="logout_admin.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main" id="main-content">
    <div class="header">
        <button class="toggle-sidebar" id="toggle-button">
            <i class="fas fa-bars"></i>
        </button>
        <h1>Kelola Data Obat</h1>
        <div class="user-info">
            Selamat Datang, <strong><?php echo isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Admin'; ?></strong>
        </div>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <div class="content">
        <div class="container">
        <!-- Button to Open Modal -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahObatModal">Tambah Obat</button> 
        
     <!-- Tabel Data Obat -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama Obat</th>
            <th>Harga Obat</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr id="row-<?= $row['id_obat']; ?>">
    <td><?= $row['id_obat']; ?></td>
    <td><?= htmlspecialchars($row['nama_obat']); ?></td>
    <td>
        <?= isset($row['harga']) && $row['harga'] !== NULL ? number_format($row['harga'], 2, ',', '.') : 'Harga tidak tersedia'; ?>
    </td>
    <td>
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editObatModal" onclick="editObat(<?= $row['id_obat']; ?>, '<?= htmlspecialchars($row['nama_obat']); ?>', <?= $row['harga']; ?>)">
            <i class="fas fa-edit"></i>Edit
        </button>
        <button class="btn btn-danger" onclick="deleteObat(<?= $row['id_obat']; ?>)">
            <i class="fas fa-trash-alt"></i> Hapus
        </button>
    </td>
</tr>

        <?php endwhile; ?>
    </tbody>
</table>

</div>

<!-- Modal Tambah Obat -->
<div class="modal fade" id="tambahObatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Obat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_obat" class="form-label">Nama Obat</label>
                        <input type="text" class="form-control" id="nama_obat" name="nama_obat" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga_obat" class="form-label">Harga Obat</label>
                        <input type="number" class="form-control" id="harga_obat" name="harga_obat" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="tambah_obat" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Obat -->
<div class="modal fade" id="editObatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Obat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_obat" id="editIdObat"> <!-- Hidden input untuk ID -->
                    <div class="mb-3">
                        <label for="editNamaObat" class="form-label">Nama Obat</label>
                        <input type="text" class="form-control" id="editNamaObat" name="nama_obat" required>
                    </div>
                    <div class="mb-3">
                        <label for="editHargaObat" class="form-label">Harga Obat</label>
                        <input type="number" class="form-control" id="editHargaObat" name="harga_obat" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="ubah_obat" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
function editObat(id, nama, harga) {
    console.log(id, nama, harga); // Debugging untuk memastikan data diteruskan dengan benar
    document.getElementById('editIdObat').value = id;
    document.getElementById('editNamaObat').value = nama;
    document.getElementById('editHargaObat').value = harga;

    // Pastikan modal muncul
    var modal = new bootstrap.Modal(document.getElementById('editObatModal'));
    modal.show();
}
    function deleteObat(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data Obat ini akan dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            // Kirim permintaan POST untuk menghapus data
            fetch('kelola_obat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `hapus=${id}`,
            })
            .then(response => response.text())
            .then(data => {
    if (data === 'success') {
        Swal.fire('Berhasil', 'Data Obat berhasil dihapus.', 'success');
        // Menghapus baris yang sesuai dari tabel
        document.getElementById(`row-${id}`).remove();
    } else {
        Swal.fire('Gagal', 'Data Obat gagal dihapus.', 'error');
    }
})

            .catch(error => {
                Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
            });
        }
    });
}


    document.getElementById('toggle-button').addEventListener('click', function() {
            var sidebar = document.getElementById('sidebar');
            var mainContent = document.getElementById('main-content');

            // Toggle class 'hidden' pada sidebar
            sidebar.classList.toggle('hidden');

            // Toggle margin-left pada main content
            mainContent.classList.toggle('full');
        });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
