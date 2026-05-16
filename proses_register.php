<?php
require_once 'config/koneksi.php';

if (isset($_POST['register'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // 1. Validasi kecocokan password
    if ($password !== $confirm) {
        header("Location: register_admin.php?status=pass_tidak_sama");
        exit();
    }

    // 2. Cek apakah username sudah ada
    $cek_user = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>
            alert('Username sudah digunakan! Silakan gunakan yang lain.');
            window.location.href='register_admin.php';
        </script>";
        exit();
    }

    // 3. Enkripsi Password (menggunakan md5 agar sesuai dengan sistem login Anda sebelumnya)
    $hashed_password = md5($password);

    // 4. Input ke Database
    // Sesuaikan nama kolom (nama, username, password) dengan tabel admin Anda
    $query = "INSERT INTO admin (username, password) VALUES ('$username', '$hashed_password')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>
            alert('Pendaftaran Berhasil! Silakan Login.');
            window.location.href='login.php';
        </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>