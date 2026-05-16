<?php
session_start();
require_once '../admin/config/koneksi.php';

if (isset($_GET['id_transaksi']) && isset($_GET['id_buku'])) {
    $id_transaksi = $_GET['id_transaksi'];
    $id_buku = $_GET['id_buku'];
    
    // MENGAMBIL TANGGAL HARI INI SECARA REAL-TIME
    $tgl_kembali_realitas = date('Y-m-d'); 
    
    // 1. Update status transaksi dan tanggal kembali asli
    $query_update = "UPDATE transaksi SET 
                     status = 'Kembali', 
                     tgl_kembali = '$tgl_kembali_realitas' 
                     WHERE id = '$id_transaksi'";
    
    if (mysqli_query($conn, $query_update)) {
        // 2. Tambah stok buku kembali
        mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE id = '$id_buku'");
        
        header("location:riwayat_pinjam.php?pesan=berhasil_kembali");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("location:riwayat_pinjam.php");
}
?>