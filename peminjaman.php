<?php
session_start();
if (!isset($_SESSION["status"])) { header("Location: login.php"); exit(); }
require_once 'config/koneksi.php';

$sql = "SELECT transaksi.*, siswa.nama_siswa, buku.judul 
        FROM transaksi 
        JOIN siswa ON transaksi.siswa_id = siswa.id 
        JOIN buku ON transaksi.buku_id = buku.id 
        ORDER BY transaksi.id DESC";
$query = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peminjaman | LibSpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-color: #4361ee; --bg-body: #f8f9fa; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-body); }
        .sidebar { height: 100vh; width: 260px; position: fixed; background: white; border-right: 1px solid #eee; }
        .main-content { margin-left: 260px; padding: 40px; }
        .nav-link { color: #6c757d; padding: 12px 20px; border-radius: 10px; margin: 5px 15px; }
        .nav-link.active { background: var(--primary-color); color: white !important; }
        .card-table { border: none; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); background: white; overflow: hidden; }
        .badge-status { padding: 6px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 700; }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column p-3">
    <div class="px-3 mb-4 mt-2"><h4 class="fw-bold text-primary"><i class="fas fa-book-reader me-2"></i>LibSpace</h4></div>
    <a href="beranda.php" class="nav-link"><i class="fas fa-grid-2 me-2"></i> Dashboard</a>
    <a href="buku.php" class="nav-link"><i class="fas fa-book me-2"></i> Koleksi Buku</a>
    <a href="anggota.php" class="nav-link"><i class="fas fa-users me-2"></i> Anggota</a>
    <a href="peminjaman.php" class="nav-link active"><i class="fas fa-exchange-alt me-2"></i> Peminjaman</a>
    <div class="mt-auto"><a href="../logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a></div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div><h2 class="fw-bold">Data Peminjaman</h2><p class="text-muted">Pantau status peminjaman buku.</p></div>
    </div>

    <div class="card-table">
        <div class="p-3"><input type="text" id="peminjamanSearch" class="form-control border-0 bg-light rounded-pill px-4" placeholder="Cari nama atau judul buku..."></div>
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="tablePinjam">
                <thead class="bg-light text-muted">
                    <tr><th class="ps-4">No</th><th>Siswa</th><th>Buku</th><th>Tgl Pinjam</th><th>Status</th><th class="text-center">Aksi</th></tr>
                </thead>
                <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td class="ps-4 text-muted small"><?= $no++ ?></td>
                        <td class="fw-bold"><?= $row['nama_siswa'] ?></td>
                        <td><?= $row['judul'] ?></td>
                        <td class="text-muted small"><?= date('d M Y', strtotime($row['tgl_pinjam'])) ?></td>
                        <td>
                            <?php if($row['status'] == 'dipinjam'): ?>
                                <span class="badge-status bg-warning bg-opacity-10 text-warning">DIPINJAM</span>
                            <?php else: ?>
                                <span class="badge-status bg-success bg-opacity-10 text-success">KEMBALI</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="hapus_peminjaman.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-light text-danger rounded-circle" onclick="return confirm('Hapus data ini?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('peminjamanSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll('#tablePinjam tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(filter) ? "" : "none";
        });
    });
</script>
</body>
</html>