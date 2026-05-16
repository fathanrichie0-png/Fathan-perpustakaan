<?php
session_start();
require_once '../admin/config/koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_siswa") {
    header("location:login_siswa.php?pesan=belum_login");
    exit();
}

if (isset($_GET['id_buku'])) {
    
    $id_buku  = mysqli_real_escape_string($conn, $_GET['id_buku']);
    
    // 1. CEK APAKAH ID_SISWA ADA DI SESSION
    if (!isset($_SESSION['id_siswa']) || empty($_SESSION['id_siswa'])) {
        die("Error: Session ID Siswa kosong. Pastikan di file login sudah ada: \$_SESSION['id_siswa'] = \$data['id'];");
    }

    $id_siswa = $_SESSION['id_siswa']; 
    $tgl_pinjam  = date('Y-m-d');
    $tgl_kembali = date('Y-m-d', strtotime('+7 days'));
    $status      = 'Dipinjam';

    // 2. VERIFIKASI APAKAH ID_SISWA BENAR-BENAR ADA DI DATABASE
    $cek_siswa = mysqli_query($conn, "SELECT id FROM siswa WHERE id = '$id_siswa'");
    if (mysqli_num_rows($cek_siswa) == 0) {
        die("Error: ID Siswa ($id_siswa) tidak ditemukan di tabel 'siswa'. Silakan login ulang atau cek tabel siswa di database.");
    }

    // 3. CEK STOK BUKU
    $query_stok = "SELECT judul, stok FROM buku WHERE id = '$id_buku'";
    $cek_stok   = mysqli_query($conn, $query_stok);
    $data_buku  = mysqli_fetch_assoc($cek_stok);

    if ($data_buku && $data_buku['stok'] > 0) {
        
        // 4. INSERT TRANSAKSI
        // CATATAN: Pastikan kolom di database kamu namanya 'siswa_id' dan 'buku_id'
        $query_insert = "INSERT INTO transaksi (siswa_id, buku_id, tgl_pinjam, tgl_kembali, status) 
                         VALUES ('$id_siswa', '$id_buku', '$tgl_pinjam', '$tgl_kembali', '$status')";
        
        $insert = mysqli_query($conn, $query_insert);

        if ($insert) {
            mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id = '$id_buku'");
            
            echo "<script>
                    alert('Berhasil meminjam buku " . $data_buku['judul'] . "');
                    window.location='riwayat_pinjam.php';
                  </script>";
            exit();
        } else {
            // Jika masih error, ini akan menampilkan pesan error MySQL yang sangat detail
            echo "<h3>Gagal Simpan Transaksi</h3>";
            echo "Error: " . mysqli_error($conn) . "<br>";
            echo "ID Siswa yang digunakan: $id_siswa <br>";
            echo "ID Buku yang digunakan: $id_buku <br>";
            die();
        }

    } else {
        echo "<script>
                alert('Stok habis!');
                window.location='daftar_buku.php';
              </script>";
        exit();
    }

} else {
    header("location:daftar_buku.php");
    exit();
}
?>