<?php
include '../admin/config/koneksi.php'; // Sesuaikan path koneksi Anda

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']); // Ini masuk ke kolom 'nis'
    $kelas    = mysqli_real_escape_string($conn, $_POST['kelas']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $status   = 'aktif';

    // Cek apakah NIS/Username sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM siswa WHERE nis = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        header("location:daftar.php?pesan=gagal");
    } else {
        // Enkripsi password agar aman (Opsional tapi disarankan)
        // Jika ingin plain text (tidak disarankan), hapus baris password_hash dan ganti $pass_db dengan $password
        $pass_db = password_hash($password, PASSWORD_DEFAULT);

        // Query INSERT (Pastikan kolom 'password' sudah dibuat di DB)
        $query = "INSERT INTO siswa (nis, nama_siswa, kelas, password, status) 
                  VALUES ('$username', '$nama', '$kelas', '$pass_db', '$status')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Pendaftaran Berhasil! Silakan Login'); window.location='login_siswa.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>