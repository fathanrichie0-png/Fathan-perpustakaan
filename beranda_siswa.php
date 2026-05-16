<?php
session_start();
require_once '../admin/config/koneksi.php';

// Proteksi login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login_siswa") {
    header("location:login_siswa.php?pesan=belum_login");
    exit();
}

$id_siswa_login = $_SESSION['id_siswa']; 
$nama_siswa     = $_SESSION['nama_siswa'];

// Query Sinkron
$q_pinjam = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE siswa_id = '$id_siswa_login' AND status = 'Dipinjam'");
$res_pinjam = mysqli_fetch_assoc($q_pinjam);
$total_pinjam = $res_pinjam['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda | worldKey</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --primary: #4361ee; 
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --card-bg: rgba(26, 28, 30, 0.6);
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)), 
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

        .hero-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 30px; 
            padding: 50px; 
            margin-top: 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        }
        
        .stat-glass {
            background: rgba(67, 97, 238, 0.15); 
            border: 1px solid rgba(67, 97, 238, 0.3);
            border-radius: 20px; 
            padding: 20px 30px; 
            display: inline-flex; 
            align-items: center; 
            gap: 15px; 
            margin-top: 25px;
        }

        .section-card { 
            background: var(--card-bg); 
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px; 
            padding: 35px; 
            margin-top: 30px; 
        }

        .search-container {
            background: rgba(255,255,255,0.05);
            border-radius: 50px;
            border: 1px solid var(--glass-border);
            padding: 5px 20px;
        }

        .table { 
            color: #ffffff !important;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table thead th {
            color: rgba(255, 255, 255, 0.5) !important;
            border: none !important;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
        }

        .table-row-custom td {
            color: #ffffff !important; 
            background: rgba(255, 255, 255, 0.03);
            border: none;
        }

        .table-row-custom td:first-child { border-radius: 15px 0 0 15px; }
        .table-row-custom td:last-child { border-radius: 0 15px 15px 0; }

        .badge-status {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .badge-status.available { background: rgba(46, 213, 115, 0.15); color: #2ed573; }
        .badge-status.empty { background: rgba(255, 71, 87, 0.15); color: #ff4757; }

        .btn-action-detail {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 24px;
            border-radius: 50px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-action-detail:hover { background: #374ecc; transform: translateY(-2px); color: white; }

        .modal-content {
            background: rgba(30, 32, 35, 0.98);
            backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border);
            color: white;
            border-radius: 25px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="beranda_siswa.php"><i class="fas fa-book-reader me-2 text-primary"></i>LIBSPACE</a>
        <div class="ms-auto d-flex align-items-center gap-4">
            <a href="beranda_siswa.php" class="nav-link active">Beranda</a>
            <a href="daftar_buku.php" class="nav-link">Daftar Buku</a>
            <a href="riwayat_pinjam.php" class="nav-link">Pinjaman Saya</a>
            <a href="logout.php" class="btn btn-outline-light btn-sm rounded-pill px-4 border-opacity-25">Keluar</a>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <div class="hero-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold display-5 mb-3">Halo, <?php echo $nama_siswa; ?>! 👋</h1>
                <p class="fs-5 opacity-75 fw-light">Akses koleksi perpustakaan digital dengan tenang dan modern.</p>
                <div class="stat-glass">
                    <i class="fas fa-book-bookmark fs-3 text-primary"></i>
                    <div>
                        <small class="d-block opacity-50 text-uppercase fw-bold" style="font-size: 0.7rem;">Buku Dipinjam</small>
                        <span class="fw-bold fs-5"><?php echo $total_pinjam; ?> Aktif</span> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section-card">
        <div class="row align-items-center mb-4">
            <div class="col-md-6"><h4 class="fw-bold m-0">Koleksi Terbaru</h4></div>
            <div class="col-md-6 mt-3 mt-md-0">
                <div class="search-container d-flex align-items-center">
                    <i class="fas fa-search text-white-50 me-2"></i>
                    <input type="text" id="searchInput" class="form-control bg-transparent border-0 text-white shadow-none" placeholder="Cari judul atau penulis...">
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table align-middle" id="bookTable">
                <thead>
                    <tr>
                        <th>Judul Buku</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil data untuk tabel
                    $data_buku = [];
                    $q = mysqli_query($conn, "SELECT * FROM buku ORDER BY id DESC LIMIT 10");
                    while($row = mysqli_fetch_assoc($q)) {
                        $data_buku[] = $row; // Simpan di array agar bisa looping modal di luar tabel
                    ?>
                    <tr class="table-row-custom">
                        <td class="py-4 fw-bold"><?php echo $row['judul']; ?></td>
                        <td class="py-4 text-white-50"><?php echo $row['penulis']; ?></td>
                        <td class="py-4">
                            <?php if($row['stok'] > 0): ?>
                                <span class="badge-status available"><i class="fas fa-circle me-2" style="font-size: 7px;"></i>Tersedia</span>
                            <?php else: ?>
                                <span class="badge-status empty"><i class="fas fa-circle me-2" style="font-size: 7px;"></i>Habis</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 text-center">
                            <button class="btn-action-detail" data-bs-toggle="modal" data-bs-target="#detail<?php echo $row['id']; ?>">Detail</button>
                        </td>
                    </tr>
                    <?php } ?>
                    
                    <tr id="noResults" style="display: none;">
                        <td colspan="4" class="text-center py-5 text-white-50">Buku tidak ditemukan.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php foreach($data_buku as $m): ?>
<div class="modal fade" id="detail<?php echo $m['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-body p-5 text-center">
                <i class="fas fa-book-open text-primary mb-4" style="font-size: 3rem;"></i>
                <h4 class="fw-bold mb-1"><?php echo $m['judul']; ?></h4>
                <div class="p-3 rounded-4 my-4" style="background: rgba(255,255,255,0.05); text-align: left;">
                    <div class="row g-2 small">
                        <div class="col-5 text-white-50">Penulis</div>
                        <div class="col-7 fw-bold"><?php echo $m['penulis']; ?></div>
                        <div class="col-5 text-white-50">Stok</div>
                        <div class="col-7 fw-bold"><?php echo $m['stok']; ?> Buku</div>
                    </div>
                </div>
                <button class="btn btn-outline-light w-100 rounded-pill py-2 border-opacity-25" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#bookTable tbody .table-row-custom');
    let found = false;

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        if(text.includes(filter)) {
            row.style.display = "";
            found = true;
        } else {
            row.style.display = "none";
        }
    });
    document.getElementById('noResults').style.display = found ? "none" : "";
});
</script>
</body>
</html>