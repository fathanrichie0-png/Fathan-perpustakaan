<?php
session_start();
// Proteksi halaman
if (!isset($_SESSION["status"])) {
    header("Location: login.php");
    exit();
}

require_once 'config/koneksi.php';

// Cek apakah ada ID yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Query Hapus (Pastikan nama kolom id_buku sesuai database kamu)
    $query = "DELETE FROM buku WHERE id_buku = '$id'";

    if (mysqli_query($conn, $query)) {
        // Jika berhasil, kirim pesan sukses melalui URL
        echo "<script>
                alert('Data buku berhasil dihapus!');
                window.location.href = 'buku.php';
              </script>";
    } else {
        // Jika gagal, tampilkan error
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
} else {
    // Jika tidak ada ID, balikkan ke halaman buku
    header("Location: buku.php");
    exit();
}
?>