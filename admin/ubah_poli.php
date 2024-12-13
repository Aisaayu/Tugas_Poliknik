<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Koneksi ke database
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'poliklinik';

    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = $_POST['id'];
    $nama_poli = $_POST['nama_poli'];
    $deskripsi = $_POST['deskripsi'];

    // Query untuk mengubah data poli
    $sql = "UPDATE poli1 SET nama_poli='$nama_poli', deskripsi='$deskripsi' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Data poli berhasil diperbarui.";
        header("Location: kelola_poli.php"); // Kembali ke halaman kelola poli
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    $id = $_GET['id'];

    // Koneksi ke database
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'poliklinik';

    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query untuk mengambil data poli berdasarkan ID
    $sql = "SELECT * FROM poli1 WHERE id='$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ubah Poli</title>
</head>
<body>
    <h2>Ubah Poli</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

        <label for="nama_poli">Nama Poli:</label>
        <input type="text" id="nama_poli" name="nama_poli" value="<?php echo $row['nama_poli']; ?>" required><br><br>

        <label for="deskripsi">Deskripsi:</label>
        <textarea id="deskripsi" name="deskripsi"><?php echo $row['deskripsi']; ?></textarea><br><br>

        <button type="submit">Perbarui</button>
    </form>
    <a href="kelola_poli.php">Kembali</a>
</body>
</html>
