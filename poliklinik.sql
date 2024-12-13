-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Des 2024 pada 12.29
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `poliklinik`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'aisaayu', '$2y$10$MuiNm/Sdz4irg.0xrnMiqOTqDa2IoTW0lnBoyPT6uDi5Z4XSpj9H6'),
(2, 'admin', '$2y$10$ICjkpQxQtrKKCIH./320beDFO9c2o8aS91DqJgS9WJYJyL3AXgb.W');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokter3`
--

CREATE TABLE `dokter3` (
  `id_dokter` int(10) UNSIGNED NOT NULL,
  `nama_dokter` varchar(100) NOT NULL,
  `spesialis` varchar(100) NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `id_poli` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dokter3`
--

INSERT INTO `dokter3` (`id_dokter`, `nama_dokter`, `spesialis`, `no_hp`, `email`, `id_poli`, `status`, `created_at`, `updated_at`) VALUES
(5, 'Dr. Ari Djoko', 'Gigi', '08134578902', 'aridjo@gmail.com', 2, 'aktif', '2024-12-11 05:34:42', '2024-12-11 14:56:22'),
(8, 'Dr. Ayu', 'Jantung', '0812345678', 'ayuuaisaa@gmail.com', 5, 'aktif', '2024-12-11 13:02:48', '2024-12-11 13:02:48'),
(14, 'Dr. Andi', 'Penyakit Dalam', '08134578902', 'Andidian@gmail.com', 4, 'aktif', '2024-12-12 17:44:58', '2024-12-12 17:44:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL,
  `dokter_id` int(11) NOT NULL,
  `waktu` time NOT NULL,
  `tanggal` date NOT NULL,
  `id_pasien` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_periksa`
--

CREATE TABLE `jadwal_periksa` (
  `id` int(11) NOT NULL,
  `id_pasien` int(11) DEFAULT NULL,
  `tanggal_periksa` date DEFAULT NULL,
  `waktu` time DEFAULT NULL,
  `dokter` varchar(255) DEFAULT NULL,
  `poli` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `obat`
--

CREATE TABLE `obat` (
  `id_obat` int(11) NOT NULL,
  `nama_obat` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL,
  `tanggal_ditambahkan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `obat`
--

INSERT INTO `obat` (`id_obat`, `nama_obat`, `deskripsi`, `harga`, `stok`, `tanggal_ditambahkan`) VALUES
(1, 'Paracetamol', 'Obat penurun demam dan pereda nyeri.', 5000.00, 100, '2024-12-12 07:06:23'),
(2, 'Amoxicillin', 'Antibiotik untuk infeksi bakteri.', 15000.00, 50, '2024-12-12 07:06:23'),
(3, 'Vitamin C', 'Suplemen untuk meningkatkan daya tahan tubuh.', 10000.00, 200, '2024-12-12 07:06:23'),
(4, 'Ibuprofen', 'Obat untuk mengurangi peradangan dan nyeri.', 8000.00, 120, '2024-12-12 07:06:23'),
(5, 'Antasida', 'Obat untuk mengatasi gangguan asam lambung.', 7000.00, 150, '2024-12-12 07:06:23'),
(6, 'Loratadine', 'Antihistamin untuk alergi.', 12000.00, 80, '2024-12-12 07:06:23'),
(7, 'Salbutamol', 'Obat untuk mengatasi sesak napas dan asma.', 20000.00, 40, '2024-12-12 07:06:23'),
(8, 'Metformin', 'Obat untuk mengontrol kadar gula darah.', 25000.00, 60, '2024-12-12 07:06:23'),
(9, 'Cetirizine', 'Obat untuk meredakan gejala alergi.', 11000.00, 90, '2024-12-12 07:06:23'),
(10, 'Ranitidine', 'Obat untuk mengatasi gangguan pencernaan.', 13000.00, 70, '2024-12-12 07:06:23'),
(11, 'Omeprazole', 'Obat untuk mengurangi produksi asam lambung.', 15000.00, 50, '2024-12-12 07:06:23'),
(12, 'Dexamethasone', 'Obat untuk mengurangi peradangan.', 18000.00, 30, '2024-12-12 07:06:23'),
(13, 'Aspirin', 'Obat untuk meredakan nyeri dan mencegah pembekuan darah.', 5000.00, 200, '2024-12-12 07:06:23'),
(14, 'Clindamycin', 'Antibiotik untuk infeksi bakteri.', 30000.00, 25, '2024-12-12 07:06:23'),
(15, 'Erythromycin', 'Antibiotik untuk berbagai jenis infeksi bakteri.', 28000.00, 35, '2024-12-12 07:06:23'),
(16, 'Hydrocortisone', 'Obat untuk mengatasi peradangan dan alergi kulit.', 15000.00, 45, '2024-12-12 07:06:23'),
(17, 'Azithromycin', 'Antibiotik untuk infeksi saluran pernapasan.', 35000.00, 20, '2024-12-12 07:06:23'),
(18, 'Tetracycline', 'Antibiotik untuk berbagai infeksi bakteri.', 24000.00, 50, '2024-12-12 07:06:23'),
(19, 'Insulin', 'Obat untuk mengontrol kadar gula darah pada diabetes.', 50000.00, 10, '2024-12-12 07:06:23'),
(20, 'Amiodarone', 'Obat untuk mengatasi gangguan irama jantung.', 45000.00, 15, '2024-12-12 07:06:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pasien`
--

CREATE TABLE `pasien` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `no_ktp` varchar(20) NOT NULL,
  `nomor_rekam_medis` varchar(20) NOT NULL,
  `tanggal_daftar` datetime NOT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pasien`
--

INSERT INTO `pasien` (`id`, `nama`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `no_hp`, `no_ktp`, `nomor_rekam_medis`, `tanggal_daftar`, `email`) VALUES
(10, 'rafi', '2003-04-01', '', 'Jalan Sambiroto Semarang', '0817171171771', '337309090003', '202412-1', '2024-12-11 19:15:18', 'raffiahmad@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftaran_pasien`
--

CREATE TABLE `pendaftaran_pasien` (
  `id` int(11) NOT NULL,
  `no_rm` varchar(50) NOT NULL,
  `alergi` varchar(50) NOT NULL,
  `alergi_jenis` text DEFAULT NULL,
  `poli` varchar(50) NOT NULL,
  `dokter` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` varchar(10) NOT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pendaftaran_pasien`
--

INSERT INTO `pendaftaran_pasien` (`id`, `no_rm`, `alergi`, `alergi_jenis`, `poli`, `dokter`, `tanggal`, `waktu`, `nama`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `no_hp`) VALUES
(1, '20240904', 'Ya', '', 'Umum', 'Dokter 2', '0000-00-00', '00:00:00', '', '0000-00-00', '', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftaran_poli`
--

CREATE TABLE `pendaftaran_poli` (
  `id` int(11) NOT NULL,
  `pasien_id` int(11) NOT NULL,
  `poli_id` int(11) NOT NULL,
  `dokter_id` int(11) NOT NULL,
  `jadwal_id` int(11) NOT NULL,
  `nomor_antrian` int(11) NOT NULL,
  `keluhan` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `poli1`
--

CREATE TABLE `poli1` (
  `id_poli` int(10) UNSIGNED NOT NULL,
  `nama_poli` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `poli1`
--

INSERT INTO `poli1` (`id_poli`, `nama_poli`, `deskripsi`, `created_at`) VALUES
(1, 'Kesehatan Jiwa ', 'Layanan pemeriksaan dan perawatan untuk kesehatan jiwa', '2024-12-11 19:40:23'),
(2, 'Gigi', 'Poli yang menangani pemeriksaan dan perawatan kesehatan gigi dan mulut, termasuk perawatan gigi berlubang, pencabutan gigi, pemasangan kawat gigi, dan perawatan lainnya', '2024-12-11 19:10:28'),
(3, 'Anak', 'Poli yang khusus untuk memeriksa dan merawat kesehatan anak, termasuk imunisasi, pemeriksaan tumbuh kembang, dan penanganan penyakit pada anak-anak.', '2024-12-11 19:10:28'),
(4, ' Penyakit Dalam', 'Poli yang menangani berbagai penyakit dalam tubuh, seperti gangguan pada sistem pencernaan, jantung, ginjal, dan pernapasan.', '2024-12-11 19:10:28'),
(5, ' Jantung', ' Poli yang berfokus pada pemeriksaan dan pengobatan penyakit jantung, seperti hipertensi, penyakit arteri koroner, gagal jantung, dan masalah jantung lainnya.', '2024-12-11 19:10:28'),
(6, ' Kandungan', 'Poli yang menangani masalah kesehatan terkait dengan kehamilan, persalinan, serta pemeriksaan dan perawatan kesehatan wanita selama dan setelah masa kehamilan.', '2024-12-11 19:10:28'),
(7, ' THT', 'Poli yang menangani masalah kesehatan pada telinga, hidung, dan tenggorokan, seperti infeksi telinga, sinusitis, gangguan pendengaran, dan penyakit tenggorokan.', '2024-12-11 19:10:28'),
(8, ' Saraf', 'Poli yang berfokus pada masalah kesehatan yang terkait dengan sistem saraf, seperti stroke, epilepsi, gangguan saraf perifer, dan penyakit saraf lainnya.', '2024-12-11 19:10:28'),
(9, ' Orthopedi', ' Poli yang menangani masalah kesehatan pada tulang, sendi, otot, dan ligamen, termasuk cedera tulang, perawatan patah tulang, serta penyakit seperti arthritis.', '2024-12-11 19:10:28'),
(10, ' Mata', 'Poli yang menangani masalah kesehatan mata, seperti gangguan penglihatan, penyakit mata, perawatan lensa kontak, dan operasi mata.', '2024-12-11 19:10:28'),
(11, ' Bedah', 'Poli yang berfokus pada tindakan operasi untuk mengatasi berbagai jenis masalah medis, termasuk operasi pengangkatan tumor, trauma fisik, dan masalah lainnya yang memerlukan prosedur bedah.', '2024-12-11 19:10:28'),
(12, ' Psikiatri', ' Poli yang menangani masalah kesehatan mental, termasuk gangguan kecemasan, depresi, gangguan bipolar, dan gangguan psikologis lainnya.', '2024-12-11 19:10:28'),
(13, ' Gizi', ' Poli yang berfokus pada masalah gizi dan diet, termasuk konsultasi mengenai pola makan sehat, penurunan berat badan, dan masalah gizi lainnya.', '2024-12-11 19:10:28'),
(14, ' Rehabilitasi Medik', ' Poli yang membantu pasien dalam proses pemulihan setelah cedera atau penyakit, seperti terapi fisik, okupasi, atau terapi bicara.', '2024-12-11 19:10:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_periksa`
--

CREATE TABLE `riwayat_periksa` (
  `id` int(11) NOT NULL,
  `id_pasien` int(11) DEFAULT NULL,
  `tanggal_periksa` date DEFAULT NULL,
  `dokter` varchar(255) DEFAULT NULL,
  `poli` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('pasien','dokter','admin') NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `nama`, `email`, `no_telp`) VALUES
(4, 'aisaayu', '$2y$10$DXxTgx/RhvKulwHwYwXtD.TeHlER48WFJzsV4U4lKjTLjwi7DChTS', 'admin', 'aisaayu', 'aisaayu@gmail.com', '0817171278291'),
(5, 'ahmadrafi', '$2y$10$1pD1DaBSTHRBjOPryIV4POaQr5CXTPlzxZv/EQp/K8BJaU2N29uE6', 'pasien', 'ahmad rafi', 'raffiahmad@gmail.com', '08123445667'),
(6, 'ayu', '$2y$10$Tt2DFElM9A4pfmdseFbxgukxtEEJTDxZYrTkX3Bu2OdSLLnxJM.wy', 'pasien', 'ayu', 'ayuuaisa@gmail.com', '082929292'),
(8, 'drayu', '$2y$10$iFPSSyvz3ME6RswQCSRTSuzQLTHEVsKkJuAZydybgpJzr5CkFAI.q', 'dokter', 'Aisa Ayu Rizky', 'aisaayu@gmail.com', '081672451901');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `dokter3`
--
ALTER TABLE `dokter3`
  ADD PRIMARY KEY (`id_dokter`),
  ADD KEY `id_poli` (`id_poli`);

--
-- Indeks untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dokter_id` (`dokter_id`);

--
-- Indeks untuk tabel `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id_obat`);

--
-- Indeks untuk tabel `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_ktp` (`no_ktp`),
  ADD UNIQUE KEY `nomor_rekam_medis` (`nomor_rekam_medis`);

--
-- Indeks untuk tabel `pendaftaran_pasien`
--
ALTER TABLE `pendaftaran_pasien`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pendaftaran_poli`
--
ALTER TABLE `pendaftaran_poli`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pasien_id` (`pasien_id`),
  ADD KEY `poli_id` (`poli_id`),
  ADD KEY `dokter_id` (`dokter_id`),
  ADD KEY `jadwal_id` (`jadwal_id`);

--
-- Indeks untuk tabel `poli1`
--
ALTER TABLE `poli1`
  ADD PRIMARY KEY (`id_poli`);

--
-- Indeks untuk tabel `riwayat_periksa`
--
ALTER TABLE `riwayat_periksa`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `dokter3`
--
ALTER TABLE `dokter3`
  MODIFY `id_dokter` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jadwal_periksa`
--
ALTER TABLE `jadwal_periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `obat`
--
ALTER TABLE `obat`
  MODIFY `id_obat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `pendaftaran_pasien`
--
ALTER TABLE `pendaftaran_pasien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pendaftaran_poli`
--
ALTER TABLE `pendaftaran_poli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `poli1`
--
ALTER TABLE `poli1`
  MODIFY `id_poli` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `riwayat_periksa`
--
ALTER TABLE `riwayat_periksa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `dokter3`
--
ALTER TABLE `dokter3`
  ADD CONSTRAINT `dokter3_ibfk_1` FOREIGN KEY (`id_poli`) REFERENCES `poli1` (`id_poli`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `fk_id_pasien` FOREIGN KEY (`id`) REFERENCES `pasien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`dokter_id`) REFERENCES `dokter` (`id`);

--
-- Ketidakleluasaan untuk tabel `pendaftaran_poli`
--
ALTER TABLE `pendaftaran_poli`
  ADD CONSTRAINT `pendaftaran_poli_ibfk_1` FOREIGN KEY (`pasien_id`) REFERENCES `pasien` (`id`),
  ADD CONSTRAINT `pendaftaran_poli_ibfk_2` FOREIGN KEY (`poli_id`) REFERENCES `poli` (`id`),
  ADD CONSTRAINT `pendaftaran_poli_ibfk_3` FOREIGN KEY (`dokter_id`) REFERENCES `dokter` (`id`),
  ADD CONSTRAINT `pendaftaran_poli_ibfk_4` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
