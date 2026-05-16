<?php
session_start();
if (!isset($_SESSION["status"])) { header("Location: login.php"); exit(); }
require_once 'config/koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM buku ORDER BY id DESC");
$query_kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Koleksi Buku | LibSpace</title>
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
        .card-table { border: none; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); background: white; }
        .img-cover { width: 45px; height: 60px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .badge-kategori { background: #eef2ff; color: #4361ee; font-size: 0.7rem; font-weight: 700; padding: 5px 10px; border-radius: 6px; }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column p-3">
    <div class="px-3 mb-4 mt-2"><h4 class="fw-bold text-primary"><i class="fas fa-book-reader me-2"></i>LibSpace</h4></div>
    <a href="beranda.php" class="nav-link"><i class="fas fa-grid-2 me-2"></i> Dashboard</a>
    <a href="buku.php" class="nav-link active"><i class="fas fa-book me-2"></i> Koleksi Buku</a>
    <a href="anggota.php" class="nav-link"><i class="fas fa-users me-2"></i> Anggota</a>
    <a href="peminjaman.php" class="nav-link"><i class="fas fa-exchange-alt me-2"></i> Peminjaman</a>
    <div class="mt-auto"><a href="../logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a></div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div><h2 class="fw-bold">Koleksi Buku</h2><p class="text-muted">Kelola database buku perpustakaan.</p></div>
        <button class="btn btn-primary px-4 py-2 rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus me-2"></i> Tambah Buku
        </button>
    </div>

    <div class="card-table">
        <div class="p-3"><input type="text" id="liveSearch" class="form-control border-0 bg-light rounded-pill px-4" placeholder="Cari judul atau penulis..."></div>
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="bukuTable">
                <thead class="bg-light text-muted">
                    <tr><th class="ps-4">Cover</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Stok</th><th class="text-center">Aksi</th></tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td class="ps-4">
                            <img src="assets/img/cover/<?= $row['cover'] ?: 'default.jpg' ?>" class="img-cover" onerror="this.src='assets/img/cover/default.jpg'">
                        </td>
                        <td class="fw-bold"><?= $row['judul'] ?></td>
                        <td class="text-muted small"><?= $row['penulis'] ?></td>
                        <td><span class="badge-kategori text-uppercase"><?= $row['kategori'] ?? 'Umum' ?></span></td>
                        <td class="fw-bold"><?= $row['stok'] ?></td>
                        <td class="text-center">
                            <button onclick="openEditModal(<?= $row['id'] ?>)" class="btn btn-sm btn-light rounded-circle text-warning"><i class="fas fa-pencil"></i></button>
                            <a href="proses_hapus_buku.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-light rounded-circle text-danger" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4">
            <form action="proses_tambah_buku.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header border-0 bg-light px-4 py-3">
                    <h5 class="fw-bold m-0">Tambah Buku Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6 mb-2">
                            <label class="small fw-bold mb-1">Judul Buku</label>
                            <input type="text" name="judul" class="form-control" required placeholder="Contoh: Harry Potter">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="small fw-bold mb-1">Kategori</label>
                            <select name="kategori" class="form-select" required>
                                <option value="" selected disabled>Pilih Kategori</option>
                                <?php mysqli_data_seek($query_kategori, 0); while($k = mysqli_fetch_assoc($query_kategori)): ?>
                                <option value="<?= $k['nama_kategori'] ?>"><?= $k['nama_kategori'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="small fw-bold mb-1">Penulis</label>
                            <input type="text" name="penulis" class="form-control" required placeholder="Nama penulis">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="small fw-bold mb-1">Stok</label>
                            <input type="number" name="stok" class="form-control" required min="1">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="small fw-bold mb-1">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" class="form-control" value="<?= date('Y') ?>">
                        </div>
                        
                        <div class="col-md-12">
                            <label class="small fw-bold mb-1">Cover Buku</label>
                            <input type="file" name="cover" class="form-control" accept="image/png, image/jpeg, image/jpg">
                            <div class="form-text text-muted small">Format: JPG/PNG. Maks: 2MB.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="submit" name="tambah" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">Simpan Koleksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4">
            <div id="contentEdit">
                <div class="p-5 text-center">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Memuat data...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Fitur Live Search
    document.getElementById('liveSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll('#bukuTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(filter) ? "" : "none";
        });
    });

    // Fitur AJAX Edit
    function openEditModal(id) {
        const modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
        modalEdit.show();
        
        fetch('get_buku.php?id=' + id)
            .then(res => {
                if (!res.ok) throw new Error("Gagal mengambil data");
                return res.text();
            })
            .then(html => {
                document.getElementById('contentEdit').innerHTML = html;
            })
            .catch(err => {
                document.getElementById('contentEdit').innerHTML = `<div class="p-4 text-danger text-center">${err.message}</div>`;
            });
    }
</script>
</body>
</html> 