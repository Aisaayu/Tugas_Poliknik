<?php
session_start();
include('includes/db.php');

// Memeriksa apakah pasien sudah login
if (!isset($_SESSION['pasien_id'])) {
    header('Location: login_pasien.php');
    exit;
}

$pasien_id = $_SESSION['pasien_id'];

if (isset($_POST['submit'])) {
    $gambar = $_FILES['gambar']['name'];
    $gambar_tmp = $_FILES['gambar']['tmp_name'];
    $gambar_size = $_FILES['gambar']['size'];
    $gambar_error = $_FILES['gambar']['error'];

    // Mengecek apakah ada file yang diupload
    if ($gambar_error === 0) {
        $ext = pathinfo($gambar, PATHINFO_EXTENSION);
        $allowed_ext = ['jpg', 'jpeg', 'png'];

        // Validasi jenis file yang diupload
        if (in_array(strtolower($ext), $allowed_ext)) {
            // Membuat nama file baru untuk menghindari duplikasi
            $new_name = "profil_" . $pasien_id . "." . $ext;
            $upload_dir = "uploads/profil/";

            // Memindahkan file ke folder tujuan
            if (move_uploaded_file($gambar_tmp, $upload_dir . $new_name)) {
                // Update database dengan nama file gambar baru
                $query = "UPDATE pasien SET gambar_profil = '$new_name' WHERE id = '$pasien_id'";
                if (mysqli_query($conn, $query)) {
                    echo "Gambar profil berhasil diunggah!";
                } else {
                    echo "Gagal memperbarui gambar profil!";
                }
            } else {
                echo "Gagal mengunggah gambar!";
            }
        } else {
            echo "Hanya gambar dengan ekstensi JPG, JPEG, dan PNG yang diperbolehkan!";
        }
    } else {
        echo "Terjadi kesalahan saat mengunggah file!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Gambar Profil</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="form-container">
        <h2>Unggah Gambar Profil</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="gambar">Pilih Gambar Profil:</label>
            <input type="file" name="gambar" required>

            <button type="submit" name="submit">Unggah</button>
        </form>
    </div>

</body>
</html>
