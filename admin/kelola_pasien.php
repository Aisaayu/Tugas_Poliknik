<?php
// Start the session
session_start();

// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$database = "poliklinik";
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk memeriksa apakah pasien sudah ada berdasarkan no_ktp
function cekPasienExist($no_ktp, $conn) {
    $stmt = $conn->prepare("SELECT * FROM pasien WHERE no_ktp = ?");
    $stmt->bind_param("s", $no_ktp);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Fungsi untuk generate nomor rekam medis
function generateNoRM($conn) {
    $tahunBulan = date("Ym");
    $stmt = $conn->prepare("SELECT COUNT(*) AS jumlah FROM pasien WHERE DATE_FORMAT(tanggal_daftar, '%Y%m') = ?");
    $stmt->bind_param("s", $tahunBulan);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $urutan = $data['jumlah'] + 1;
    return $tahunBulan . "-" . $urutan;
}

// Menambah pasien
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $no_ktp = $_POST['no_ktp'];
    $email = $_POST['email'];

    if (cekPasienExist($no_ktp, $conn)) {
        //echo "Pasien dengan No KTP $no_ktp sudah terdaftar.";
    } else {
        $no_rm = generateNoRM($conn);
        $tanggal_daftar = date("Y-m-d H:i:s");

        $stmt = $conn->prepare("INSERT INTO pasien (nama, tanggal_lahir, jenis_kelamin, alamat, no_hp, no_ktp, nomor_rekam_medis, tanggal_daftar, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $nama, $tanggal_lahir, $jenis_kelamin, $alamat, $no_hp, $no_ktp, $no_rm, $tanggal_daftar, $email);

        if ($stmt->execute()) {
            //echo "Pasien berhasil ditambahkan dengan No Rekam Medis: $no_rm";
        } else {
          //  echo "Gagal menambahkan pasien: " . $stmt->error;
        }
    }
}

// Mengedit pasien
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE pasien SET nama = ?, tanggal_lahir = ?, jenis_kelamin = ?, alamat = ?, no_hp = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $nama, $tanggal_lahir, $jenis_kelamin, $alamat, $no_hp, $email, $id);

    if ($stmt->execute()) {
       // echo "Pasien berhasil diperbarui.";
    } else {
       // echo "Gagal memperbarui pasien: " . $stmt->error;
    }
}

// Menghapus pasien
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM pasien WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        //echo "Pasien berhasil dihapus.";
    } else {
       // echo "Gagal menghapus pasien: " . $stmt->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Pasien</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        <h1>Kelola Data Pasien</h1>
        <div class="user-info">
            Selamat Datang, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>
        </div>
    </div>
    <div class="content">
        <div class="container">
            <!-- Button to Open Modal -->
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahPasienModal">Tambah Pasien</button> 
            

            <!-- Modal -->
            <div class="modal fade" id="tambahPasienModal" tabindex="-1" aria-labelledby="tambahPasienModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahPasienModalLabel">Tambah Pasien</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                                </div>
                                <div class="mb-3">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" required>
                                </div>
                                <div class="mb-3">
                                    <label for="no_hp" class="form-label">No HP</label>
                                    <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                                </div>
                                <div class="mb-3">
                                    <label for="no_ktp" class="form-label">No KTP</label>
                                    <input type="text" class="form-control" id="no_ktp" name="no_ktp" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <button type="submit" class="btn btn-primary" name="tambah">Tambah Pasien</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No RM</th>
                        <th>Nama</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Alamat</th>
                        <th>No HP</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Menampilkan data pasien
                    $query = "SELECT * FROM pasien";
                    $result = $conn->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['nomor_rekam_medis'] . "</td>";
                        echo "<td>" . $row['nama'] . "</td>";
                        echo "<td>" . $row['tanggal_lahir'] . "</td>";
                        echo "<td>" . ($row['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan') . "</td>";
                        echo "<td>" . $row['alamat'] . "</td>";
                        echo "<td>" . $row['no_hp'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>
<button class='btn btn-warning btn-sm' onclick='editPasien(" . $row['id'] . ", \"" . $row['nama'] . "\", \"" . $row['tanggal_lahir'] . "\", \"" . $row['jenis_kelamin'] . "\", \"" . $row['alamat'] . "\", \"" . $row['no_hp'] . "\", \"" . $row['email'] . "\")'>
    <i class='fas fa-edit'></i> Edit
</button>
<button type='button' class='btn btn-danger btn-sm' onclick='confirmDelete(" . $row['id'] . ")'>
    <i class='fas fa-trash'></i> Hapus
</button>
                            </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Pasien -->
<div class="modal fade" id="editPasienModal" tabindex="-1" aria-labelledby="editPasienModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPasienModalLabel">Edit Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPasienForm">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="mb-3">
                        <label for="edit-nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="edit-nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="edit-tanggal_lahir" name="tanggal_lahir" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-control" id="edit-jenis_kelamin" name="jenis_kelamin" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit-alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="edit-alamat" name="alamat" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-no_hp" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="edit-no_hp" name="no_hp" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit-email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="edit">Update Pasien</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function editPasien(id, nama, tanggal_lahir, jenis_kelamin, alamat, no_hp, email) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-nama').value = nama;
    document.getElementById('edit-tanggal_lahir').value = tanggal_lahir;
    document.getElementById('edit-jenis_kelamin').value = jenis_kelamin;
    document.getElementById('edit-alamat').value = alamat;
    document.getElementById('edit-no_hp').value = no_hp;
    document.getElementById('edit-email').value = email;
    
    var myModal = new bootstrap.Modal(document.getElementById('editPasienModal'));
    myModal.show();
}

document.getElementById('editPasienForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    var formData = new FormData(this);
    formData.append('edit', true);  // Append 'edit' action to the form data

    fetch('kelola_pasien.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert('Data berhasil diperbarui');
        location.reload(); // Reload the page to see the changes
    })
    .catch(error => {
        alert('Terjadi kesalahan');
    });
});

    document.getElementById('toggle-button').addEventListener('click', function() {
            var sidebar = document.getElementById('sidebar');
            var mainContent = document.getElementById('main-content');

            // Toggle class 'hidden' pada sidebar
            sidebar.classList.toggle('hidden');

            // Toggle margin-left pada main content
            mainContent.classList.toggle('full');
        });
        function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data pasien ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Create form to submit the delete request
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = ''; // This should point to the same page

            // Create hidden input for the id
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id';
            input.value = id;
            form.appendChild(input);

            // Append 'hapus' action
            var deleteAction = document.createElement('input');
            deleteAction.type = 'hidden';
            deleteAction.name = 'hapus';
            form.appendChild(deleteAction);

            // Append the form to the body and submit
            document.body.appendChild(form);
            form.submit();
        }
    });
}


</script>
</body>
</html>
