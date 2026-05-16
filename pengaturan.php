<?php
session_start();
if (!isset($_SESSION["status"])) {
    header("Location: login.php"); 
    exit();
}
require_once 'config/koneksi.php'; 

$username = $_SESSION['username'] ?? 'Admin';

// Ambil data pengaturan dari database (Asumsi tabel 'pengaturan' dengan ID 1)
$query = mysqli_query($conn, "SELECT * FROM pengaturan WHERE id = 1");
$data = mysqli_fetch_assoc($query);

// Menggunakan data dari DB atau nilai default jika data belum ada
$nama_perpus = $data['nama_perpus'] ?? "LibSpace Library";
$alamat_perpus = $data['alamat'] ?? "Jl. Pendidikan No. 45, Bandung";
$lama_pinjam = $data['lama_pinjam'] ?? 3;
$denda_per_hari = $data['denda'] ?? 1000;

$status = $_GET['status'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan | LibSpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-color: #4361ee; --bg-body: #f8f9fa; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-body); color: #2b2d42; }
        
        /* Sidebar */
        .sidebar { height: 100vh; width: 260px; position: fixed; background: #fff; border-right: 1px solid rgba(0,0,0,0.05); z-index: 1000; }
        .main-content { margin-left: 260px; padding: 40px; }
        .nav-link { color: #6c757d; padding: 12px 20px; border-radius: 10px; margin: 5px 15px; font-weight: 500; text-decoration: none; display: block; }
        .nav-link.active { background: var(--primary-color); color: white !important; box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2); }
        
        /* Cards */
        .card-settings { border: none; border-radius: 20px; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.02); margin-bottom: 24px; }
        .section-title { font-size: 1.1rem; font-weight: 700; color: var(--primary-color); margin-bottom: 20px; display: flex; align-items: center; }
        .section-title i { margin-right: 10px; }
        
        /* Form Styling */
        .form-label { font-weight: 600; font-size: 0.9rem; color: #6c757d; }
        .form-control { border-radius: 12px; padding: 12px 15px; border: 1px solid #eee; background: #fcfcfc; }
        .form-control:focus { box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.05); border-color: var(--primary-color); outline: none; }
        .input-group-text { border-radius: 12px; border: 1px solid #eee; background: #f8f9fa; color: #6c757d; font-weight: 600; }
        
        .btn-save { background: var(--primary-color); border: none; border-radius: 30px; padding: 12px; font-weight: 700; color: white; transition: 0.3s; width: 100%; }
        .btn-save:hover { background: #304fd0; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3); }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column p-3">
    <div class="px-3 mb-4 mt-2"><h4 class="fw-bold text-primary"><i class="fas fa-book-reader me-2"></i>LibSpace</h4></div>
    <a href="beranda.php" class="nav-link"><i class="fas fa-grid-2 me-2"></i> Dashboard</a>
    <a href="buku.php" class="nav-link"><i class="fas fa-book me-2"></i> Koleksi Buku</a>
    <a href="anggota.php" class="nav-link"><i class="fas fa-users me-2"></i> Anggota</a>
    <a href="peminjaman.php" class="nav-link"><i class="fas fa-exchange-alt me-2"></i> Peminjaman</a>
    <a href="pengaturan.php" class="nav-link active"><i class="fas fa-cog me-2"></i> Pengaturan</a>
    <div class="mt-auto"><a href="../logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a></div>
</div>

<div class="main-content">
    <div class="mb-5">
        <h2 class="fw-bold">Pengaturan Sistem</h2>
        <p class="text-muted">Kelola identitas resmi dan kebijakan operasional perpustakaan.</p>
    </div>

    <?php if ($status == 'sukses'): ?>
        <div class="alert alert-success border-0 rounded-4 mb-4 py-3 shadow-sm">
            <i class="fas fa-check-circle me-2"></i> Pengaturan berhasil disimpan ke database.
        </div>
    <?php elseif ($status == 'error'): ?>
        <div class="alert alert-danger border-0 rounded-4 mb-4 py-3 shadow-sm">
            <i class="fas fa-exclamation-circle me-2"></i> Gagal menyimpan perubahan.
        </div>
    <?php endif; ?>

    <form action="proses_update_pengaturan.php" method="POST">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card-settings p-4 p-md-5">
                    <div class="section-title"><i class="fas fa-university"></i> Identitas Perpustakaan</div>
                    <div class="mb-4">
                        <label class="form-label">Nama Perpustakaan</label>
                        <input type="text" name="nama_perpus" class="form-control" value="<?= htmlspecialchars($nama_perpus) ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Alamat / Lokasi Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="4" required><?= htmlspecialchars($alamat_perpus) ?></textarea>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card-settings p-4">
                    <div class="section-title"><i class="fas fa-gavel"></i> Kebijakan Peminjaman</div>
                    <div class="mb-4">
                        <label class="form-label">Durasi Pinjam Maksimal</label>
                        <div class="input-group">
                            <input type="number" name="lama_pinjam" class="form-control" value="<?= $lama_pinjam ?>" required>
                            <span class="input-group-text border-start-0">Hari</span>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Denda Per Hari</label>
                        <div class="input-group">
                            <span class="input-group-text border-end-0">Rp</span>
                            <input type="number" name="denda" class="form-control" value="<?= $denda_per_hari ?>" required>
                        </div>
                    </div>
                </div>

                <div class="card-settings p-4 border border-primary border-opacity-10 bg-primary bg-opacity-10 shadow-sm">
                    <h6 class="fw-bold mb-3">Simpan Perubahan?</h6>
                    <p class="text-muted small mb-4">Perubahan ini akan langsung diterapkan pada seluruh laporan dan struk peminjaman.</p>
                    <button type="submit" name="save_settings" class="btn btn-save">
                        Simpan Semua Pengaturan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>