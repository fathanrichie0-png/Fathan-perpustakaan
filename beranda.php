<?php
session_start();
if (!isset($_SESSION["status"])) {
    header("Location: login.php"); 
    exit();
}
require_once 'config/koneksi.php'; 

$username = $_SESSION['username'] ?? 'Admin';
$role     = $_SESSION['role'] ?? 'Petugas';

// Statistik
$q_buku = mysqli_query($conn, "SELECT COUNT(*) as total FROM buku");
$total_buku = mysqli_fetch_assoc($q_buku)['total'] ?? 0;

$q_anggota = mysqli_query($conn, "SELECT COUNT(*) as total FROM siswa");
$total_anggota = mysqli_fetch_assoc($q_anggota)['total'] ?? 0;

$q_pinjam = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE DATE(tgl_pinjam) = CURDATE()");
$total_pinjam = mysqli_fetch_assoc($q_pinjam)['total'] ?? 0;

// Estimasi Denda
$tarif_denda = 1000; 
$durasi_pinjam = 3; 
$q_denda = mysqli_query($conn, "SELECT SUM((DATEDIFF(CURDATE(), tgl_pinjam) - $durasi_pinjam) * $tarif_denda) as total_estimasi 
                                FROM transaksi WHERE status = 'dipinjam' AND DATEDIFF(CURDATE(), tgl_pinjam) > $durasi_pinjam");
$total_estimasi_denda = mysqli_fetch_assoc($q_denda)['total_estimasi'] ?? 0;

// Grafik 7 Hari
$label_grafik = []; $data_grafik = [];
for ($i = 6; $i >= 0; $i--) {
    $tgl = date('Y-m-d', strtotime("-$i days"));
    $label_grafik[] = date('d M', strtotime($tgl));
    $res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jumlah FROM transaksi WHERE DATE(tgl_pinjam) = '$tgl'"));
    $data_grafik[] = $res['jumlah'] ?? 0;
}

// Perbaikan Join Query sesuai struktur tabel (id_siswa & id_buku)
$q_terbaru = mysqli_query($conn, "SELECT t.*, s.nama_siswa, b.judul FROM transaksi t 
                LEFT JOIN siswa s ON t.id = s.id 
                LEFT JOIN buku b ON t.id = b.id ORDER BY t.id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | LibSpace Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --sidebar-bg: #ffffff; --primary-color: #4361ee; --bg-body: #f8f9fa; --text-main: #2b2d42; }
        [data-bs-theme="dark"] { --sidebar-bg: #1a1c1e; --bg-body: #111315; --text-main: #f8f9fa; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-body); color: var(--text-main); }
        .sidebar { height: 100vh; width: 260px; position: fixed; background: var(--sidebar-bg); border-right: 1px solid rgba(0,0,0,0.05); z-index: 1000; }
        .main-content { margin-left: 260px; padding: 40px; }
        .card-custom { border: none; border-radius: 20px; background: var(--sidebar-bg); box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: 0.3s; }
        .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .nav-link { color: #6c757d; padding: 12px 20px; border-radius: 10px; margin: 5px 15px; font-weight: 500; }
        .nav-link.active { background: var(--primary-color); color: white !important; }
        
        /* Dropdown Profile Styling */
        .profile-dropdown .dropdown-toggle::after { display: none; }
        .profile-dropdown img { cursor: pointer; transition: 0.2s; border: 2px solid transparent; }
        .profile-dropdown img:hover { border-color: var(--primary-color); }
        .dropdown-menu { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 10px; }
        .dropdown-item { border-radius: 8px; padding: 10px 15px; font-weight: 500; font-size: 0.9rem; }
        .dropdown-item i { width: 20px; }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column p-3">
    <div class="px-3 mb-4 mt-2"><h4 class="fw-bold text-primary"><i class="fas fa-book-reader me-2"></i>LibSpace</h4></div>
    <a href="beranda.php" class="nav-link active"><i class="fas fa-grid-2 me-2"></i> Dashboard</a>
    <a href="buku.php" class="nav-link"><i class="fas fa-book me-2"></i> Koleksi Buku</a>
    <a href="anggota.php" class="nav-link"><i class="fas fa-users me-2"></i> Anggota</a>
    <a href="peminjaman.php" class="nav-link"><i class="fas fa-exchange-alt me-2"></i> Peminjaman</a>
    <div class="mt-auto"><a href="../logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a></div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between mb-5">
        <div><h2 class="fw-bold">Ringkasan Hari Ini</h2><p class="text-muted">Selamat datang kembali, <?= $username ?>.</p></div>
        
        <div class="dropdown profile-dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name=<?= $username ?>&background=4361ee&color=fff" class="rounded-circle shadow-sm" width="45">
            </a>
            <ul class="dropdown-menu dropdown-menu-end p-2">
                <li><div class="px-3 py-2"><p class="mb-0 fw-bold small"><?= $username ?></p><p class="mb-0 text-muted extra-small" style="font-size: 0.75rem;"><?= $role ?></p></div></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="profil.php"><i class="fas fa-user-circle me-2 text-primary"></i> Profil Saya</a></li>
                <li><a class="dropdown-item" href="pengaturan.php"><i class="fas fa-cog me-2 text-secondary"></i> Pengaturan</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i> Keluar</a></li>
            </ul>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card-custom p-4">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary mb-3"><i class="fas fa-book"></i></div>
                <p class="text-muted small mb-1 fw-bold">TOTAL BUKU</p>
                <h3 class="fw-bold m-0"><?= number_format($total_buku) ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom p-4">
                <div class="stat-icon bg-success bg-opacity-10 text-success mb-3"><i class="fas fa-users"></i></div>
                <p class="text-muted small mb-1 fw-bold">ANGGOTA</p>
                <h3 class="fw-bold m-0"><?= number_format($total_anggota) ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom p-4">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning mb-3"><i class="fas fa-clock"></i></div>
                <p class="text-muted small mb-1 fw-bold">PINJAM HARI INI</p>
                <h3 class="fw-bold m-0"><?= number_format($total_pinjam) ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom p-4 bg-primary text-white">
                <div class="stat-icon bg-white bg-opacity-20 mb-3"><i class="fas fa-wallet"></i></div>
                <p class="small mb-1 fw-bold">KAS DENDA</p>
                <h3 class="fw-bold m-0">Rp <?= number_format($total_estimasi_denda, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-custom p-4 mb-4">
                <h5 class="fw-bold mb-4">Grafik Peminjaman</h5>
                <canvas id="chartPinjam" height="120"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-custom p-4">
                <h5 class="fw-bold mb-4">Aktivitas Terakhir</h5>
                <?php if(mysqli_num_rows($q_terbaru) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($q_terbaru)): ?>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0"><div class="stat-icon bg-light text-muted"><i class="fas fa-user small"></i></div></div>
                        <div class="ms-3">
                            <p class="mb-0 fw-bold small"><?= $row['nama_siswa'] ?: 'Siswa tidak ditemukan' ?></p>
                            <p class="mb-0 text-muted" style="font-size: 0.7rem;"><?= $row['judul'] ?: 'Buku tidak ditemukan' ?></p>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-muted small italic">Belum ada aktivitas hari ini.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('chartPinjam'), {
        type: 'line',
        data: {
            labels: <?= json_encode($label_grafik) ?>,
            datasets: [{ 
                label: 'Pinjaman', 
                data: <?= json_encode($data_grafik) ?>, 
                borderColor: '#4361ee', 
                tension: 0.4, 
                fill: true, 
                backgroundColor: 'rgba(67, 97, 238, 0.05)' 
            }]
        },
        options: { 
            plugins: { legend: { display: false } }, 
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } 
        }
    });
</script>
</body>
</html>