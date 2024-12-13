<?php
session_start();

// Debugging: Periksa session
if (!isset($_SESSION['username']) || !isset($_SESSION['pasien_id'])) {
    echo "Session tidak ditemukan. Harap login kembali.";
    header("Location: login_pasien.php");
    exit;
}

$username = $_SESSION['username'];
$pasien_id = $_SESSION['pasien_id'];

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "poliklinik");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query data pasien
$pasien_query = "SELECT * FROM pasien1 WHERE id = '$pasien_id'";
$pasien_result = $conn->query($pasien_query);

if ($pasien_result->num_rows == 0) {
    echo "Data pasien tidak ditemukan.";
    exit;
}

$pasien = $pasien_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Poli</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #4682B4;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #5a9bd3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pendaftaran Poli</h2>
        <form action="proses_pendaftaran_poli.php" method="POST">
            <!-- Informasi Pasien -->
            <fieldset>
                <legend><strong>Informasi Pasien</strong></legend>
                <label for="nama">Nama Pasien</label>
                <input type="text" id="nama" name="nama" value="<?php echo $pasien['nama']; ?>" readonly>

                <label for="no_rm">No. Rekam Medis</label>
                <input type="text" id="no_rm" name="no_rm" value="<?php echo $pasien['no_rekam_medis']; ?>" readonly>

                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo $pasien['tanggal_lahir']; ?>" readonly>

                <label for="jenis_kelamin">Jenis Kelamin</label>
                <input type="text" id="jenis_kelamin" name="jenis_kelamin" value="<?php echo $pasien['jenis_kelamin']; ?>" readonly>

                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" readonly><?php echo $pasien['alamat']; ?></textarea>
            </fieldset>

            <!-- Pendaftaran Poli -->
            <fieldset>
                <legend><strong>Daftar Poli</strong></legend>
                <label for="poli">Pilih Poli</label>
                <select id="poli" name="poli" required>
                    <option value="">Pilih Poli</option>
                    <?php while ($poli = $poli_result->fetch_assoc()) { ?>
                        <option value="<?php echo $poli['id']; ?>"><?php echo $poli['nama_poli']; ?></option>
                    <?php } ?>
                </select>

                <label for="dokter">Pilih Dokter</label>
                <select id="dokter" name="dokter" required>
                    <option value="">Pilih Dokter</option>
                    <?php while ($dokter = $dokter_result->fetch_assoc()) { ?>
                        <option value="<?php echo $dokter['id']; ?>"><?php echo $dokter['nama_dokter']; ?></option>
                    <?php } ?>
                </select>

                <label for="tanggal_pendaftaran">Tanggal Pendaftaran</label>
                <input type="date" id="tanggal_pendaftaran" name="tanggal_pendaftaran" required>

                <label for="waktu_pendaftaran">Waktu Pendaftaran</label>
                <input type="time" id="waktu_pendaftaran" name="waktu_pendaftaran" required>

                <label for="jenis_pendaftaran">Jenis Pendaftaran</label>
                <select id="jenis_pendaftaran" name="jenis_pendaftaran" required>
                    <option value="Baru">Baru</option>
                    <option value="Kontrol">Kontrol</option>
                </select>
            </fieldset>

            <!-- Informasi Kesehatan -->
            <fieldset>
                <legend><strong>Informasi Kesehatan</strong></legend>
                <label for="riwayat_penyakit">Riwayat Penyakit</label>
                <textarea id="riwayat_penyakit" name="riwayat_penyakit"></textarea>

                <label for="alergi">Alergi</label>
                <textarea id="alergi" name="alergi"></textarea>

                <label for="kondisi_sekarang">Kondisi Kesehatan Saat Ini</label>
                <textarea id="kondisi_sekarang" name="kondisi_sekarang"></textarea>

                <label for="tujuan">Tujuan Pendaftaran</label>
                <textarea id="tujuan" name="tujuan"></textarea>
            </fieldset>

            <!-- Konfirmasi -->
            <fieldset>
                <legend><strong>Konfirmasi Pendaftaran</strong></legend>
                <label>Konfirmasi Nama Pasien: <strong><?php echo $pasien['nama']; ?></strong></label>
                <label>Konfirmasi Tanggal & Waktu Pendaftaran: <strong><span id="konfirmasi_tanggal_waktu"></span></strong></label>
                <label>Konfirmasi Poli & Dokter: <strong><span id="konfirmasi_poli_dokter"></span></strong></label>
            </fieldset>

            <!-- Tombol -->
            <button type="submit">Submit</button>
            <button type="reset">Reset</button>
            <button type="button" onclick="window.print()">Cetak Kartu Antrian</button>
        </form>
    </div>
</body>
</html>
