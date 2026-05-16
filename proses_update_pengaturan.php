<?php
session_start();

// Proteksi halaman - hanya admin yang bisa akses
if (!isset($_SESSION["status"]) || $_SESSION["status"] != "login") {
    header("Location: login.php");
    exit();
}

require_once 'config/koneksi.php';

if (isset($_POST['save_settings'])) {
    // Ambil dan sanitize data dari form
    $nama_perpus = mysqli_real_escape_string($conn, $_POST['nama_perpus']);
    $alamat      = mysqli_real_escape_string($conn, $_POST['alamat']);
    $lama_pinjam = (int)$_POST['lama_pinjam'];
    $denda       = (float)$_POST['denda'];

    // Validasi sederhana
    if (empty($nama_perpus) || empty($alamat) || $lama_pinjam <= 0 || $denda < 0) {
        header("Location: pengaturan.php?status=error");
        exit();
    }

    // Update data di database (gunakan id=1 untuk satu settings)
    $query = "UPDATE pengaturan SET 
              nama_perpus = '$nama_perpus',
              alamat      = '$alamat',
              lama_pinjam = $lama_pinjam,
              denda       = $denda
              WHERE id = 1";

    if (mysqli_query($conn, $query)) {
        // Log aktivitas (opsional)
        error_log("Pengaturan diperbarui oleh " . $_SESSION['username'] . " pada " . date('Y-m-d H:i:s'));

        // Redirect dengan status sukses
        header("Location: pengaturan.php?status=sukses");
        exit();
    } else {
        // Jika query gagal
        error_log("Gagal update pengaturan: " . mysqli_error($conn));
        header("Location: pengaturan.php?status=error");
        exit();
    }
} else {
    // Jika尝试 akses langsung tanpa submit form
    header("Location: pengaturan.php");
    exit();
}
?>
