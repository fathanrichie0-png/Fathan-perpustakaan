<?php
session_start();
require_once '../admin/config/koneksi.php';

if (isset($_GET['id'])) {
    $id_pinjam = $_GET['id'];
    
    // 1. Ambil tanggal hari ini secara otomatis
    $tgl_kembali_asli = date('Y-m-d'); 
    
    // 2. Update tabel peminjaman
    // Kita set status jadi 'Kembali' dan tgl_kembali sesuai tanggal saat ini
    $query_update = "UPDATE peminjaman SET 
                     tgl_kembali = '$tgl_kembali_asli', 
                     status = 'Kembali' 
                     WHERE id_peminjaman = '$id_pinjam'";

    if (mysqli_query($conn, $query_update)) {
        // 3. Kembalikan stok buku (+1)
        // Ambil ID buku terkait terlebih dahulu
        $get_buku = mysqli_query($conn, "SELECT id_buku FROM peminjaman WHERE id_peminjaman = '$id_pinjam'");
        $data_buku = mysqli_fetch_assoc($get_buku);
        $id_buku = $data_buku['id_buku'];

        mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE id = '$id_buku'");

        echo "<script>
                alert('Buku berhasil dikembalikan pada tanggal $tgl_kembali_asli');
                window.location.href='riwayat_pinjam.php';
              </script>";
    } else {
        echo "Gagal memproses pengembalian: " . mysqli_error($conn);
    }
} else {
    header("Location: riwayat_pinjam.php");
}
?>