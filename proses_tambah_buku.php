<?php
require_once 'config/koneksi.php'; // Pastikan path koneksi benar

if (isset($_POST['tambah'])) {
    // Gunakan mysqli_real_escape_string untuk keamanan dasar dari SQL Injection
    $kode_buku    = mysqli_real_escape_string($conn, $_POST['kode_buku']);
    $judul        = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis      = mysqli_real_escape_string($conn, $_POST['penulis']);
    $penerbit     = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tahun_terbit = mysqli_real_escape_string($conn, $_POST['tahun_terbit']);
    $kategori     = mysqli_real_escape_string($conn, $_POST['kategori']);
    $stok         = mysqli_real_escape_string($conn, $_POST['stok']);

    // Logika Upload Gambar
    $nama_file = $_FILES['cover']['name'];
    $ukuran_file = $_FILES['cover']['size'];
    $tmp_name = $_FILES['cover']['tmp_name'];
    $error = $_FILES['cover']['error'];

    if ($error === 0) {
        $ekstensiValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = explode('.', $nama_file);
        $ekstensiGambar = strtolower(end($ekstensiGambar));

        if (in_array($ekstensiGambar, $ekstensiValid)) {
            $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
            
            // Pastikan folder assets/img/cover/ sudah ada
            if (!file_exists('assets/img/cover/')) {
                mkdir('assets/img/cover/', 0777, true);
            }
            
            move_uploaded_file($tmp_name, 'assets/img/cover/' . $namaFileBaru);
            $cover = $namaFileBaru;
        } else {
            echo "<script>alert('Format gambar salah!'); window.location='buku.php';</script>";
            exit;
        }
    } else {
        $cover = 'default.jpg';
    }

    // --- BAGIAN YANG HARUS KAMU PERHATIKAN ---
    // Pastikan nama kolom 'kategori' di bawah ini sama persis dengan yang ada di phpMyAdmin
    // Jika di database namanya 'id_kategori', maka ganti 'kategori' menjadi 'id_kategori'
    $query = "INSERT INTO buku (kode_buku, judul, penulis, penerbit, tahun_terbit, kategori, stok, cover) 
              VALUES ('$kode_buku', '$judul', '$penulis', '$penerbit', '$tahun_terbit', '$kategori', '$stok', '$cover')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location='buku.php';</script>";
    } else {
        // Ini akan memunculkan error spesifik jika nama kolom masih salah
        die("Kesalahan Database: " . mysqli_error($conn));
    }
}
?>