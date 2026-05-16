<?php
session_start();
require_once '../admin/config/koneksi.php';

// Proteksi Session
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_siswa") {
    header("location:login_siswa.php?pesan=belum_login");
    exit();
}

$id_siswa_login = $_SESSION['id_siswa'];

// Query Ambil Data (Pastikan nama tabel dan kolom sesuai database Anda)
// Saya menggunakan 'transaksi' sesuai kode Anda, ganti 'id_peminjaman' jika perlu
$query = mysqli_query($conn, "SELECT t.*, b.judul, b.penulis 
                               FROM transaksi t
                               JOIN buku b ON t.buku_id = b.id 
                               WHERE t.siswa_id = '$id_siswa_login' 
                               ORDER BY t.id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjaman Saya | LibSpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root { 
            --primary: #4361ee; 
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --card-bg: rgba(26, 28, 30, 0.6);
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), 
                        url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: #ffffff;
            min-height: 100vh;
        }

        .navbar { 
            background: rgba(26, 28, 30, 0.8) !important; 
            backdrop-filter: blur(15px); 
            border-bottom: 1px solid var(--glass-border); 
            padding: 15px 0;
        }
        .navbar-brand { color: #ffffff !important; font-weight: 700; letter-spacing: 1px; }
        .nav-link { color: rgba(255,255,255,0.7) !important; font-weight: 500; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { color: var(--primary) !important; }

        .card-history { 
            background: var(--card-bg); 
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 28px; 
            padding: 25px; 
            transition: all 0.3s ease; 
            height: 100%;
        }
        .card-history:hover {
            transform: translateY(-8px);
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(67, 97, 238, 0.3);
        }

        .status-badge { 
            font-size: 0.7rem; padding: 6px 14px; border-radius: 50px; 
            font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .badge-dipinjam { background: rgba(255, 159, 67, 0.15); color: #ff9f43; }
        .badge-kembali { background: rgba(46, 213, 115, 0.15); color: #2ed573; }

        .info-box {
            background: rgba(255, 255, 255, 0.03); border-radius: 18px;
            padding: 15px; margin: 15px 0; border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .btn-kembalikan {
            background: var(--primary); color: white; border: none;
            border-radius: 15px; padding: 10px; font-weight: 700;
            transition: 0.3s; box-shadow: 0 4px 15px rgba(67, 97, 238, 0.2);
        }
        .btn-kembalikan:hover { background: #374ecc; color: white; transform: scale(1.02); }
        
        .btn-done {
            background: rgba(46, 213, 115, 0.1); color: #2ed573;
            border: 1px solid rgba(46, 213, 115, 0.2); border-radius: 15px;
            font-weight: 600; cursor: default;
        }

        /* Custom Swal */
        .swal2-popup { background: #1e2023 !important; color: white !important; border-radius: 25px !important; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="beranda_siswa.php"><i class="fas fa-book-reader me-2 text-primary"></i>LIBSPACE</a>
        <div class="ms-auto d-flex align-items-center gap-4">
            <a href="beranda_siswa.php" class="nav-link">Beranda</a>
            <a href="daftar_buku.php" class="nav-link">Daftar Buku</a>
            <a href="riwayat_pinjam.php" class="nav-link active">Pinjaman Saya</a>
            <a href="logout.php" class="btn btn-outline-light btn-sm rounded-pill px-4 border-opacity-25 ms-2">Keluar</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="mb-5">
        <h2 class="fw-bold m-0">Pinjaman Saya</h2>
        <p class="text-white-50 m-0">Pantau status peminjaman dan riwayat literasi Anda.</p>
    </div>

    <div class="row g-4">
        <?php if(mysqli_num_rows($query) > 0): ?>
            <?php while($data = mysqli_fetch_array($query)): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card-history d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-box" style="width: 45px; height: 45px; background: rgba(67, 97, 238, 0.15); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                                <i class="fas fa-book"></i>
                            </div>
                            <span class="status-badge <?php echo (strtolower($data['status']) == 'dipinjam') ? 'badge-dipinjam' : 'badge-kembali'; ?>">
                                <i class="fas fa-circle me-1" style="font-size: 6px;"></i>
                                <?php echo $data['status']; ?>
                            </span>
                        </div>
                        
                        <h6 class="fw-bold mb-1 text-truncate"><?php echo $data['judul']; ?></h6>
                        <p class="text-white-50 small mb-0"><?php echo $data['penulis']; ?></p>
                        
                        <div class="info-box mt-auto">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-white-50 small">Tgl. Pinjam</small>
                                <span class="fw-bold small"><?php echo date('d M Y', strtotime($data['tgl_pinjam'])); ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-white-50 small">Tgl. Kembali</small>
                                <span class="fw-bold small <?php echo (strtolower($data['status']) == 'dipinjam') ? 'text-primary' : 'text-white-50'; ?>">
                                    <?php echo date('d M Y', strtotime($data['tgl_kembali'])); ?>
                                </span>
                            </div>
                        </div>

                        <?php if(strtolower($data['status']) == 'dipinjam'): ?>
                            <button onclick="konfirmasiKembali('<?php echo $data['id']; ?>', '<?php echo $data['buku_id']; ?>', '<?php echo $data['judul']; ?>')" 
                                    class="btn btn-kembalikan w-100 py-2">
                                Kembalikan Sekarang
                            </button>
                        <?php else: ?>
                            <div class="btn btn-done w-100 py-2 btn-sm">
                                <i class="fas fa-check-circle me-2"></i>Selesai
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="opacity-25 mb-3"><i class="fas fa-box-open fa-4x"></i></div>
                <h5 class="fw-bold opacity-50">Belum Ada Peminjaman</h5>
                <a href="daftar_buku.php" class="btn btn-primary rounded-pill px-4 mt-2">Cari Buku</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function konfirmasiKembali(idTransaksi, idBuku, judulBuku) {
    Swal.fire({
        title: 'Kembalikan Buku?',
        text: `Apakah Anda yakin ingin mengembalikan buku "${judulBuku}"? Tanggal kembali akan dicatat hari ini.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4361ee',
        cancelButtonColor: 'rgba(255,255,255,0.1)',
        confirmButtonText: 'Ya, Kembalikan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `kembali_buku.php?id_transaksi=${idTransaksi}&id_buku=${idBuku}`;
        }
    })
}
</script>

</body>
</html>