<?php
include('includes/db.php');
include('includes/functions.php');
include('includes/header.php');
include('includes/sidebar.php');

// Mendapatkan ID obat yang akan diedit
$id = $_GET['id'];

// Mengambil data obat berdasarkan ID
$query = "SELECT * FROM obat WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$obat = mysqli_fetch_assoc($result);

// Mengupdate data obat
if (isset($_POST['update_obat'])) {
    $nama_obat = $_POST['nama_obat'];
    $jenis_obat = $_POST['jenis_obat'];
    $harga = $_POST['harga'];

    $sql = "UPDATE obat SET nama_obat = '$nama_obat', jenis_obat = '$jenis_obat', harga = '$harga' WHERE id = '$id'";
    if (mysqli_query($conn, $sql)) {
        echo "Obat berhasil diperbarui!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<div class="content">
    <h2>Edit Obat</h2>
    
    <!-- Form Edit Obat -->
    <form method="POST">
        <label for="nama_obat">Nama Obat</label>
        <input type="text" name="nama_obat" value="<?php echo $obat['nama_obat']; ?>" required>
        
        <label for="jenis_obat">Jenis Obat</label>
        <input type="text" name="jenis_obat" value="<?php echo $obat['jenis_obat']; ?>" required>
        
        <label for="harga">Harga</label>
        <input type="number" name="harga" value="<?php echo $obat['harga']; ?>" required>
        
        <button type="submit" name="update_obat">Perbarui Obat</button>
    </form>
</div>

<?php include('includes/footer.php'); ?>
