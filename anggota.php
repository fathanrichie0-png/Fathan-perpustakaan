<?php
session_start();
if (!isset($_SESSION["status"])) { header("Location: login.php"); exit(); }
require_once 'config/koneksi.php';
$query = mysqli_query($conn, "SELECT * FROM siswa ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Anggota | LibSpace</title>
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
        .avatar-small { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column p-3">
    <div class="px-3 mb-4 mt-2"><h4 class="fw-bold text-primary"><i class="fas fa-book-reader me-2"></i>LibSpace</h4></div>
    <a href="beranda.php" class="nav-link"><i class="fas fa-grid-2 me-2"></i> Dashboard</a>
    <a href="buku.php" class="nav-link"><i class="fas fa-book me-2"></i> Koleksi Buku</a>
    <a href="anggota.php" class="nav-link active"><i class="fas fa-users me-2"></i> Anggota</a>
    <a href="peminjaman.php" class="nav-link"><i class="fas fa-exchange-alt me-2"></i> Peminjaman</a>
    <div class="mt-auto"><a href="../logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a></div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div><h2 class="fw-bold">Manajemen Anggota</h2><p class="text-muted">Daftar siswa yang terdaftar.</p></div>
    </div>

    <div class="card-table">
        <div class="p-3"><input type="text" id="searchInput" class="form-control border-0 bg-light rounded-pill px-4" placeholder="Cari NIS atau nama..."></div>
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="memberTable">
                <thead class="bg-light text-muted">
                    <tr><th class="ps-4">No</th><th>Nama Siswa</th><th>NIS</th><th>Kelas</th><th class="text-center">Aksi</th></tr>
                </thead>
                <tbody>
                    <?php $no=1; while($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td class="ps-4 text-muted small"><?= $no++ ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="https://ui-avatars.com/api/?name=<?= $row['nama_siswa'] ?>&background=random" class="avatar-small">
                                <span class="fw-bold"><?= $row['nama_siswa'] ?></span>
                            </div>
                        </td>
                        <td class="text-muted"><?= $row['nis'] ?></td>
                        <td><span class="badge bg-light text-dark"><?= $row['kelas'] ?></span></td>
                        <td class="text-center">
                            <button onclick="openEditModal('<?= $row['id'] ?>', '<?= $row['nis'] ?>', '<?= $row['nama_siswa'] ?>', '<?= $row['kelas'] ?>')" class="btn btn-sm btn-light text-warning rounded-circle"><i class="fas fa-pencil"></i></button>
                            <a href="hapus_anggota.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-light text-danger rounded-circle" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<div class="modal fade" id="modalEditAnggota" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 rounded-4">
            <form action="proses_edit_anggota.php" method="POST">
                <div class="modal-header border-0 bg-light p-4"><h5 class="fw-bold m-0">Edit Data Anggota</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3"><label class="small fw-bold">NIS</label><input type="text" name="nis" id="edit_nis" class="form-control" required></div>
                    <div class="mb-3"><label class="small fw-bold">Nama Lengkap</label><input type="text" name="nama_siswa" id="edit_nama" class="form-control" required></div>
                    <div class="mb-3"><label class="small fw-bold">Kelas</label><input type="text" name="kelas" id="edit_kelas" class="form-control" required></div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0"><button type="submit" name="edit" class="btn btn-warning w-100 rounded-pill py-2 text-white">Simpan Perubahan</button></div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // JAVASCRIPT UNTUK MENGISI DATA KE MODAL EDIT
    function openEditModal(id, nis, nama, kelas) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nis').value = nis;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_kelas').value = kelas;

        var myModal = new bootstrap.Modal(document.getElementById('modalEditAnggota'));
        myModal.show();
    }

    // Fitur Search Sederhana
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll('#memberTable tbody tr').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(filter) ? "" : "none";
        });
    });
</script>
</body>
</html>