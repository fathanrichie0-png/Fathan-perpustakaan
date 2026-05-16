<?php
// Mengarahkan ke file koneksi yang ada di folder utama perpus2
include '../admin/config/koneksi.php'; 

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'create') {
    // Cek apakah data dikirim melalui method POST
    if (isset($_POST['nis'], $_POST['nama_siswa'], $_POST['kelas'])) {
        
        $nis = mysqli_real_escape_string($conn, $_POST['nis']);
        $nama = mysqli_real_escape_string($conn, $_POST['nama_siswa']);
        $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);
        $status = 'aktif';

        if (empty($nis) || empty($nama)) {
            echo "<script>alert('Data tidak boleh kosong!'); window.history.back();</script>";
            exit;
        }

        $query = "INSERT INTO siswa (nis, nama_siswa, kelas, status) VALUES ('$nis', '$nama', '$kelas', '$status')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Berhasil menambah data!'); window.location='index.php';</script>";
        } else {
            echo "Error Database: " . mysqli_error($conn);
        }
    } else {
        // Ini muncul jika file diakses langsung tanpa lewat form register
        echo "Data form tidak lengkap. Silakan isi form dari halaman register.";
    }
}
?>