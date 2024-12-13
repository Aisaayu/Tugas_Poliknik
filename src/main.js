import './style.css';

// Fungsi untuk mendapatkan data dokter berdasarkan ID
async function getDokter(id) {
    try {
        // Mengirim permintaan ke server untuk mendapatkan data dokter
        const response = await fetch(`path/to/get_dokter.php?id=${id}`);
        const data = await response.json();
        
        if (data.error) {
            alert(data.error);  // Menampilkan error jika ada
        } else {
            // Menampilkan data dokter di halaman
            document.getElementById('doctor-name').textContent = data.name;
            document.getElementById('doctor-specialty').textContent = data.specialty;
        }
    } catch (error) {
        console.error('Error fetching dokter data:', error);
    }
}

// Inisialisasi konten halaman
const app = document.getElementById('app');
app.innerHTML = `
  <h1>Dashboard Pasien</h1>
  <p>Selamat datang, ${nama_pasien}</p>
  <p>Anda telah login sebagai pasien poliklinik.</p>
  
  <!-- Menambahkan elemen untuk menampilkan informasi dokter -->
  <div>
    <h2 id="doctor-name">Loading...</h2>
    <p id="doctor-specialty">Loading...</p>
  </div>
`;

// Menjalankan fungsi untuk mengambil data dokter dengan ID tertentu
getDokter(1);  // Gantilah dengan ID dokter yang sesuai
