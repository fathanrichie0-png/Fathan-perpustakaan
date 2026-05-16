<?php
session_start();
require_once '../admin/config/koneksi.php';

// 1. Proteksi Session
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_siswa") {
    header("location:login_siswa.php?pesan=belum_login");
    exit();
}

$id_siswa_login = $_SESSION['id_siswa'];

// --- LOGIC: CEK APAKAH SISWA SUDAH MEMINJAM BUKU ---
$cek_pinjaman = mysqli_query($conn, "SELECT * FROM transaksi WHERE siswa_id = '$id_siswa_login' AND status = 'Pinjam'");
$jumlah_pinjam = mysqli_num_rows($cek_pinjaman);

// 2. Query Ambil Data Buku
$query_buku = mysqli_query($conn, "SELECT * FROM buku ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku | LibSpace</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css">
    
    <style>
        :root { 
            --primary: #ea3c3c; 
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

        .book-card { 
            background: var(--card-bg); 
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 28px; 
            padding: 25px; 
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            height: 100%; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            text-align: center;
        }
        .book-card:hover { 
            transform: translateY(-12px); 
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(238, 67, 93, 0.4);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        .cover-wrapper {
            width: 100%;
            height: 220px;
            margin-bottom: 20px;
            overflow: hidden;
            border-radius: 18px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
            border: 1px solid var(--glass-border);
        }
        .cover-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .badge-status { 
            font-size: 0.75rem; 
            padding: 6px 16px; 
            border-radius: 50px; 
            margin-bottom: 15px; 
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .status-available { background: rgba(46, 213, 115, 0.15); color: #2ed573; }
        .status-empty { background: rgba(255, 71, 87, 0.15); color: #ff4757; }
        .status-locked { background: rgba(255, 165, 0, 0.15); color: #ffa500; }
        
        .btn-pinjam { 
            background: var(--primary); 
            border: none; 
            border-radius: 15px; 
            padding: 12px; 
            font-weight: 700; 
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(238, 67, 67, 0.3);
            color: white;
            text-decoration: none;
        }
        .btn-pinjam:hover { 
            background: #cc3737; 
            transform: translateY(-2px);
            color: white;
        }
        .btn-detail { 
            border-radius: 15px; 
            font-weight: 600; 
            color: #ffffff; 
            border: 1px solid rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.05);
            padding: 10px;
        }

        .modal-content {
            background: rgba(30, 32, 35, 0.98);
            backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border);
            color: white;
            border-radius: 30px;
        }

        /* Custom SweetAlert Style */
        .swal2-popup {
            border-radius: 25px !important;
            background: #1a1c1e !important;
            border: 1px solid var(--glass-border) !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="beranda_siswa.php"><i class="fas fa-book-reader me-2 text-primary"></i>LIBSPACE</a>
        <div class="ms-auto d-flex align-items-center gap-4">
            <a href="beranda_siswa.php" class="nav-link">Beranda</a>
            <a href="daftar_buku.php" class="nav-link active">Daftar Buku</a>
            <a href="riwayat_pinjam.php" class="nav-link">Pinjaman Saya</a>
            <a href="logout.php" class="btn btn-outline-light btn-sm rounded-pill px-4 border-opacity-25 ms-2">Keluar</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold display-6">Koleksi Perpustakaan</h2>
        <p class="opacity-50">Jelajahi ribuan ilmu pengetahuan yang tersusun rapi untuk Anda.</p>
        
        <?php if($jumlah_pinjam >= 1): ?>
            <div class="alert alert-warning d-inline-block rounded-4 border-0 py-2 px-4 mb-4" style="background: rgba(255, 165, 0, 0.1); color: #ffa500;">
                <i class="fas fa-exclamation-triangle me-2"></i> Kamu memiliki 1 pinjaman aktif. Selesaikan dulu untuk meminjam lagi.
            </div>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <?php while($buku = mysqli_fetch_array($query_buku)): ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="book-card">
                <?php if($buku['stok'] <= 0): ?>
                    <span class="badge-status status-empty"><i class="fas fa-times-circle me-2"></i>Stok Habis</span>
                <?php elseif($jumlah_pinjam >= 1): ?>
                    <span class="badge-status status-locked"><i class="fas fa-lock me-2"></i>Limit Tercapai</span>
                <?php else: ?>
                    <span class="badge-status status-available"><i class="fas fa-check-circle me-2"></i>Tersedia</span>
                <?php endif; ?>

                <div class="cover-wrapper">
                    <?php 
                        $foto = $buku['cover'];
                        $path = "../admin/assets/img/cover/" . $foto;
                        $tampil_foto = (!empty($foto) && file_exists($path)) ? $path : "../admin/assets/img/cover/default.jpg";
                    ?>
                    <img src="<?php echo $tampil_foto; ?>" class="cover-img" alt="Cover Buku">
                </div>

                <h6 class="fw-bold mb-1 text-truncate w-100"><?php echo $buku['judul']; ?></h6>
                <p class="text-white-50 small mb-4"><?php echo $buku['penulis']; ?></p>

                <div class="w-100 d-grid gap-2">
                    <button class="btn btn-detail w-100" data-bs-toggle="modal" data-bs-target="#detail<?php echo $buku['id']; ?>">
                        Detail Buku
                    </button>
                    
                    <?php if($buku['stok'] > 0 && $jumlah_pinjam < 1): ?>
                        <a href="proses_pinjam.php?id_buku=<?php echo $buku['id']; ?>" 
                           class="btn btn-pinjam btn-confirm-pinjam" 
                           data-judul="<?php echo $buku['judul']; ?>">
                           Pinjam Sekarang
                        </a>
                    <?php elseif($jumlah_pinjam >= 1 && $buku['stok'] > 0): ?>
                        <button class="btn btn-secondary w-100 disabled opacity-50" style="border-radius: 15px;">
                            Kuota Habis
                        </button>
                    <?php else: ?>
                        <button class="btn btn-secondary w-100 disabled opacity-25" style="border-radius: 15px;">Habis</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="detail<?php echo $buku['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-body p-5 text-center">
                        <img src="<?php echo $tampil_foto; ?>" class="img-fluid mb-4 shadow-lg" style="width: 150px; border-radius: 15px;">
                        <h4 class="fw-bold mb-1"><?php echo $buku['judul']; ?></h4>
                        <p class="text-white-50 small mb-4">Informasi Bibliografi</p>
                        <div class="text-start p-4 rounded-4 mb-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                            <div class="row mb-3">
                                <div class="col-5 text-white-50 small">Penulis</div>
                                <div class="col-7 fw-bold small"><?php echo $buku['penulis']; ?></div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-5 text-white-50 small">Ketersediaan</div>
                                <div class="col-7 fw-bold small text-primary"><?php echo $buku['stok']; ?> Buku</div>
                            </div>
                        </div>
                        <button class="btn btn-outline-light w-100 rounded-pill py-2 border-opacity-25" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Konfigurasi SweetAlert2 untuk tombol Pinjam
    document.querySelectorAll('.btn-confirm-pinjam').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            const judul = this.getAttribute('data-judul');

            Swal.fire({
                title: 'Konfirmasi Pinjam',
                html: `Apakah Anda yakin ingin meminjam buku <br><b>${judul}</b>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ea3c3c', // Warna primary
                cancelButtonColor: 'rgba(255,255,255,0.1)',
                confirmButtonText: 'Ya, Pinjam Sekarang',
                cancelButtonText: 'Batal',
                background: '#1a1c1e',
                color: '#fff',
                backdrop: `rgba(0,0,0,0.6)`
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>

</body>
</html>