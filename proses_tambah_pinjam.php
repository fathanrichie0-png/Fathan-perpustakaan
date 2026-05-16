<?php
// 1. Perbaikan Path Koneksi (Gunakan ../ untuk naik satu level folder)
require_once 'config/koneksi.php';

if (isset($_POST['simpan'])) {
    // 2. Tangkap data dari form (Pastikan name di form sesuai)
    $siswa_id   = $_POST['siswa_id'];
    $buku_id    = $_POST['buku_id'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $status     = "Dipinjam";

    // 3. Query INSERT (Hanya kolom yang ada di database kamu)
    $sql = "INSERT INTO transaksi (siswa_id, buku_id, tgl_pinjam, status) 
            VALUES ('$siswa_id', '$buku_id', '$tgl_pinjam', '$status')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Berhasil! Transaksi telah dicatat.');
                window.location.href='peminjaman.php';
              </script>";
    } else {
        echo "Error Database: " . mysqli_error($conn);
    }
} else {
    header("Location: peminjaman.php");
}
?>