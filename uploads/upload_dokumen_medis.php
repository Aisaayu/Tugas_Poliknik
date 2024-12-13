<?php
session_start();
include('includes/db.php');

// Memeriksa apakah dokter sudah login
if (!isset($_SESSION['dokter_id'])) {
    header('Location: login_dokter.php');
    exit;
}

$dokter_id = $_SESSION['dokter_id'];

if (isset($_POST['submit'])) {
    $dokumen = $_FILES['dokumen']['name'];
    $dokumen_tmp = $_FILES['dokumen']['tmp_name'];
    $dokumen_size = $_FILES['dokumen']['size'];
    $dokumen_error = $_FILES['dokumen']['error'];

    // Mengecek apakah ada file yang diupload
    if ($dokumen_error === 0) {
        $ext = pathinfo($dokumen, PATHINFO_EXTENSION);
        $allowed_ext = ['pdf', 'docx', 'txt'];

        // Validasi jenis file yang diupload
        if (in_array(strtolower($ext), $allowed_ext)) {
            // Membuat nama file baru untuk menghindari duplikasi
            $new_name = "dokumen_" . $dokter_id . "_" . time() . "." . $ext;
            $upload_dir = "uploads/dokumen/";

            // Memindahkan file ke folder tujuan
            if (move_uploaded_file($dokumen_tmp, $upload_dir . $new_name)) {
                // Simpan informasi dokumen dalam database
                $query = "INSERT INTO dokumen_medis (dokter_id, nama_dokumen) 
                          VALUES ('$dokter_id', '$new_name')";
                if (mysqli_query($conn, $query)) {
                    echo "Dokumen medis berhasil diunggah!";
                } else {
                    echo "Gagal menyimpan dokumen dalam database!";
                }
            } else {
                echo "Gagal mengunggah dokumen!";
            }
        } else {
            echo "Hanya dokumen dengan ekstensi PDF, DOCX, dan TXT yang diperbolehkan!";
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
    <title>Upload Dokumen Medis</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="form-container">
        <h2>Unggah Dokumen Medis</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="dokumen">Pilih Dokumen Medis:</label>
            <input type="file" name="dokumen" required>

            <button type="submit" name="submit">Unggah</button>
        </form>
    </div>

</body>
</html>
