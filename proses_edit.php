<?php
session_start();
if (!isset($_SESSION["status"])) {
    header("Location: login.php");
    exit();
}

require_once 'config/koneksi.php';

if (isset($_POST['update'])) {
    // 1. Ambil ID dari input hidden (name="id_buku" di form)
    // Tapi kita gunakan untuk mencari kolom 'id' di database
    $id           = mysqli_real_escape_string($conn, $_POST['id_buku']);
    
    // 2. Amankan data input lainnya
    $judul        = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis      = mysqli_real_escape_string($conn, $_POST['penulis']);
    $penerbit     = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tahun_terbit = mysqli_real_escape_string($conn, $_POST['tahun_terbit']);
    $stok         = mysqli_real_escape_string($conn, $_POST['stok']);

    // 3. FIX QUERY: Ubah 'id_buku' menjadi 'id' agar sesuai dengan database Anda
    $sql = "UPDATE buku SET 
            judul        = '$judul', 
            penulis      = '$penulis', 
            penerbit     = '$penerbit', 
            tahun_terbit = '$tahun_terbit', 
            stok         = '$stok' 
            WHERE id     = '$id'"; // <-- Bagian ini yang krusial

    if (mysqli_query($conn, $sql)) {
        // Redirect balik ke tabel buku dengan pesan sukses
        header("Location: buku.php?pesan=update-berhasil");
        exit();
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($conn);
    }
} else {
    header("Location: buku.php");
    exit();
}
?>