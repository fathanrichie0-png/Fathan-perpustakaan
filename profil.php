<?php
session_start();
if (!isset($_SESSION["status"])) {
    header("Location: login.php"); 
    exit();
}
require_once 'config/koneksi.php'; 

$username = $_SESSION['username'] ?? 'Admin';
$role     = $_SESSION['role'] ?? 'Petugas';

// Ambil data foto terbaru dari database admin
$query_user = mysqli_query($conn, "SELECT foto FROM admin WHERE username = '$username'");
$data_user = mysqli_fetch_assoc($query_user);

// Cek jika ada foto di database, jika tidak pakai UI Avatars
$foto_profil = (!empty($data_user['foto'])) ? 'uploads/' . $data_user['foto'] : "https://ui-avatars.com/api/?name=$username&background=4361ee&color=fff&size=128";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Saya | LibSpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { --primary-color: #4361ee; --bg-body: #f8f9fa; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-body); color: #2b2d42; }
        
        .sidebar { height: 100vh; width: 260px; position: fixed; background: #fff; border-right: 1px solid rgba(0,0,0,0.05); z-index: 1000; }
        .main-content { margin-left: 260px; padding: 40px; }
        .nav-link { color: #6c757d; padding: 12px 20px; border-radius: 10px; margin: 5px 15px; font-weight: 500; }
        .nav-link.active { background: var(--primary-color); color: white !important; }
        
        .card-profile { border: none; border-radius: 24px; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.02); overflow: hidden; }
        .profile-cover { background: linear-gradient(135deg, #4361ee 0%, #4cc9f0 100%); height: 140px; }
        
        /* Avatar Styling */
        .profile-avatar-wrapper { position: relative; margin-top: -60px; margin-bottom: 15px; cursor: pointer; display: inline-block; }
        .profile-avatar { width: 120px; height: 120px; border-radius: 30px; border: 5px solid #fff; object-fit: cover; box-shadow: 0 10px 20px rgba(0,0,0,0.1); transition: 0.3s; }
        .profile-avatar-wrapper:hover .profile-avatar { filter: brightness(0.9); }
        .camera-overlay { position: absolute; bottom: 10px; right: 10px; background: var(--primary-color); color: white; width: 32px; height: 32px; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 3px solid #fff; font-size: 0.8rem; }
        
        /* Perbaikan Baris 48: Menggunakan text-transform bukan text-uppercase */
        .info-label { font-size: 0.75rem; font-weight: 700; color: #adb5bd; text-transform: uppercase; letter-spacing: 1px; }
        
        .form-control { border-radius: 12px; padding: 12px 15px; border: 1px solid #eee; background: #fcfcfc; }
        .btn-save { background: var(--primary-color); border: none; border-radius: 12px; padding: 12px 30px; font-weight: 600; transition: 0.3s; }
        .badge-role { background: rgba(67, 97, 238, 0.1); color: var(--primary-color); font-weight: 700; font-size: 0.7rem; padding: 6px 12px; border-radius: 8px; text-transform: uppercase; }
    </style>
</head>
<body>

<?php
// Logika SweetAlert2 berdasarkan parameter URL
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'sukses') {
        echo "<script>Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Profil Anda telah diperbarui.', showConfirmButton: false, timer: 2000 });</script>";
    } else if ($_GET['status'] == 'error') {
        echo "<script>Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan sistem.' });</script>";
    } else if ($_GET['status'] == 'password_salah') {
        echo "<script>Swal.fire({ icon: 'warning', title: 'Cek Password!', text: 'Konfirmasi password tidak cocok.' });</script>";
    }
}
?>

<div class="sidebar d-flex flex-column p-3">
    <div class="px-3 mb-4 mt-2"><h4 class="fw-bold text-primary"><i class="fas fa-book-reader me-2"></i>LibSpace</h4></div>
    <a href="beranda.php" class="nav-link"><i class="fas fa-grid-2 me-2"></i> Dashboard</a>
    <a href="buku.php" class="nav-link"><i class="fas fa-book me-2"></i> Koleksi Buku</a>
    <a href="anggota.php" class="nav-link"><i class="fas fa-users me-2"></i> Anggota</a>
    <a href="peminjaman.php" class="nav-link"><i class="fas fa-exchange-alt me-2"></i> Peminjaman</a>
    <div class="mt-auto"><a href="../logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a></div>
</div>

<div class="main-content">
    <div class="mb-5">
        <h2 class="fw-bold">Profil Pengguna</h2>
        <p class="text-muted">Kelola informasi pribadi dan keamanan akun Anda.</p>
    </div>

    <form action="proses_update_profil.php" method="POST" enctype="multipart/form-data">
        <div class="row g-4">
            <div class="col-lg-4 text-center">
                <div class="card-profile pb-4">
                    <div class="profile-cover"></div>
                    <div class="profile-avatar-wrapper" onclick="document.getElementById('inputFoto').click();" title="Klik untuk ganti foto">
                        <img src="<?= $foto_profil ?>" class="profile-avatar" id="previewFoto">
                        <div class="camera-overlay"><i class="fas fa-camera"></i></div>
                        <input type="file" name="foto" id="inputFoto" hidden accept="image/*" onchange="previewImage(this)">
                    </div>
                    <h4 class="fw-bold mb-1"><?= $username ?></h4>
                    <div class="mb-4"><span class="badge-role"><?= $role ?></span></div>
                    <hr class="mx-4 opacity-5">
                    <div class="row px-4 mt-3">
                        <div class="col-6 text-start">
                            <p class="info-label mb-1">Status</p>
                            <p class="fw-bold small text-success"><i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i> Online</p>
                        </div>
                        <div class="col-6 text-end">
                            <p class="info-label mb-1">Terdaftar</p>
                            <p class="fw-bold small">Apr 2026</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card-profile p-4 p-md-5">
                    <h5 class="fw-bold mb-4">Detail Akun</h5>
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label info-label">Username</label>
                            <input type="text" class="form-control text-muted" value="<?= $username ?>" disabled>
                            <div class="form-text small italic text-muted mt-2">Username bersifat permanen dan tidak dapat diubah oleh petugas.</div>
                        </div>
                        <div class="col-12"><hr class="my-2 opacity-5"></div>
                        <div class="col-12">
                            <h6 class="fw-bold mb-3 mt-2 text-primary"><i class="fas fa-lock me-2"></i>Ubah Keamanan</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label info-label">Password Baru</label>
                            <input type="password" name="password_baru" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label info-label">Konfirmasi Password</label>
                            <input type="password" name="konfirmasi_password" class="form-control" placeholder="••••••••">
                        </div>
                        <div class="col-12 mt-5 text-end">
                            <button type="submit" name="update_profil" class="btn btn-primary btn-save px-5 shadow-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Fungsi untuk preview foto secara instant sebelum upload
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewFoto').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>