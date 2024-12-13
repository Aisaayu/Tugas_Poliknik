<?php
session_start(); // Mulai sesi untuk bisa mengakses $_SESSION
include '../includes/db.php'; // Koneksi ke database

// Menangani proses edit
if (isset($_POST['edit'])) {
    $id_poli = $_POST['id'] ?? '';  // Mendapatkan id_poli yang dikirimkan
    if (!empty($id_poli)) {
        // Ambil data baru dari form edit
        $nama_poli = $_POST['nama_poli'];
        $deskripsi = $_POST['deskripsi'];

        // Query untuk memperbarui data
        $sql = "UPDATE poli1 SET nama_poli = ?, deskripsi = ? WHERE id_poli = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nama_poli, $deskripsi, $id_poli);

        if ($stmt->execute()) {
           // echo "Data berhasil diperbarui!";
        } else {
           // echo "Terjadi kesalahan: " . $stmt->error;
        }

        $stmt->close();
    } else {
        //echo "ID Poli tidak ditemukan.";
    }
}
if (isset($_POST['hapus'])) {
    $id_poli = $_POST['hapus'];
    $stmt = $conn->prepare("DELETE FROM poli1 WHERE id_poli = ?");
    $stmt->bind_param("i", $id_poli);
    
    if ($stmt->execute()) {
        echo "success"; // Mengirimkan respons sukses
    } else {
        echo "error"; // Mengirimkan respons gagal
    }
    exit;
}

if (isset($_POST['tambah_poli'])) {
    $nama_poli = $_POST['nama_poli'];
    $deskripsi = $_POST['deskripsi'];

    // Insert data poli ke database
    $stmt = $conn->prepare("INSERT INTO poli1 (nama_poli, deskripsi) VALUES (?, ?)");
    $stmt->bind_param("ss", $nama_poli, $deskripsi);

    if ($stmt->execute()) {
       // echo "<script>Swal.fire('Berhasil', 'Poli berhasil ditambahkan!', 'success');</script>";
    } else {
      //  echo "<script>Swal.fire('Gagal', 'Gagal menambahkan poli.', 'error');</script>";
    }
}

// Menampilkan daftar poli
$sql = "SELECT * FROM poli1";
$result = $conn->query("SELECT * FROM poli1");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Poli</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
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
        <h1>Kelola Data Poli</h1>
        <div class="user-info">
            Selamat Datang, <strong><?php echo isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Admin'; ?></strong>
        </div>
    </div>
    
    <div class="content">
        <div class="container">
        <!-- Button to Open Modal -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahPoliModal">Tambah Poli</button> 
        

        <!-- Modal -->
        <div class="modal fade" id="tambahPoliModal" tabindex="-1" aria-labelledby="tambahPoliModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahPoliModalLabel">Tambah Poli</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="nama_poli" class="form-label">Nama Poli</label>
                                <input type="text" class="form-control" id="nama_poli" name="nama_poli" required>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" name="tambah_poli">Tambah Poli</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    
    <table class="table table-bordered">
    <thead>
    <tr>
            <th>ID. Poli</th>
            <th>Nama Poli</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
                <tr id="row-<?= $row['id_poli']; ?>">
                    <td><?= $row['id_poli']; ?></td>
                    <td><?= $row['nama_poli']; ?></td>
                    <td><?= $row['deskripsi']; ?></td>
                <td>
                <button class="btn btn-warning" onclick="editPoli(<?= $row['id_poli']; ?>, '<?= $row['nama_poli']; ?>', '<?= $row['deskripsi']; ?>')">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger" onclick="deletePoli(<?= $row['id_poli']; ?>)">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
</td>
            </tr>
        <?php endwhile; ?>
        </tbody>

    </table>
</div>

<!-- Modal Edit Poli -->
<div class="modal" tabindex="-1" id="editModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editForm" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Edit Poli</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="editId">
          <div class="mb-3">
            <label for="editNama" class="form-label">Nama Poli</label>
            <input type="text" class="form-control" id="editNama" name="nama_poli" required>
          </div>
          <div class="mb-3">
            <label for="editDeskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="editDeskripsi" name="deskripsi" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="edit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    function editPoli(id, nama, deskripsi) {
        // Set values to modal form
        document.getElementById('editId').value = id;
        document.getElementById('editNama').value = nama;
        document.getElementById('editDeskripsi').value = deskripsi;

        // Show modal
        var myModal = new bootstrap.Modal(document.getElementById('editModal'));
        myModal.show();
    }

    function deletePoli(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data poli ini akan dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            // Kirim permintaan POST untuk menghapus data
            fetch('kelola_poli.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `hapus=${id}`,
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    Swal.fire('Berhasil', 'Data poli berhasil dihapus.', 'success');
                    document.getElementById(`row-${id}`).remove(); // Hapus baris dari tabel
                } else {
                    Swal.fire('Gagal', 'Data poli gagal dihapus.', 'error');
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

<?php
$conn->close(); // Menutup koneksi database
?>
