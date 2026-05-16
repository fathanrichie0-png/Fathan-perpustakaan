<?php
session_start();
include '../admin/config/koneksi.php';

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$username'");
$data = mysqli_fetch_assoc($query);

if ($data) {
    if (password_verify($password, $data['password'])) {
        // SIMPAN KE SESSION DISINI
        $_SESSION['id_siswa']   = $data['id'];         // Mengambil kolom 'id' dari database
        $_SESSION['nama_siswa'] = $data['nama_siswa']; // Mengambil kolom 'nama_siswa'
        $_SESSION['status']     = "login_siswa";
        
        header("location:beranda_siswa.php");
    } else {
        header("location:login_siswa.php?pesan=gagal");
    }
} else {
    header("location:login_siswa.php?pesan=gagal");
}
?>