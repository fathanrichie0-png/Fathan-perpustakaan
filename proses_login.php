<?php
session_start();
require_once 'config/koneksi.php';

if (isset($_POST['login'])) {
    // Ambil data dari form login
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Enkripsi password yang dimasukkan user agar cocok dengan yang ada di database
    // PENTING: Gunakan md5 jika di register/reset kamu pakai md5
    $password_enkripsi = md5($password);

    // Cari user di tabel admin
    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username' AND password = '$password_enkripsi'");
    
    // Hitung jumlah data yang ditemukan
    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($query);

        // Set session untuk tanda login berhasil
        $_SESSION['status']   = "login";
        $_SESSION['username'] = $data['username'];
        $_SESSION['id_admin'] = $data['id']; // Opsional, untuk profil

        // Arahkan ke dashboard (beranda.php)
        header("Location: beranda.php");
    } else {
        // Jika gagal, kembali ke login dengan status error
        header("Location: login.php?pesan=gagal");
    }
} else {
    header("Location: login.php");
}
?>