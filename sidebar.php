<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --sidebar-width: 280px;
        --primary-accent: #4361ee; /* Warna biru modern sesuai dashboard */
        --bg-sidebar: #ffffff;
        --text-muted: #6c757d;
        --text-dark: #2b2d42;
        --nav-hover-bg: rgba(67, 97, 238, 0.08);
    }

    /* Support Dark Mode if needed */
    [data-bs-theme="dark"] :root {
        --bg-sidebar: #1a1c1e;
        --text-dark: #f8f9fa;
        --nav-hover-bg: rgba(255, 255, 255, 0.05);
    }

    .sidebar {
        height: 100vh;
        width: var(--sidebar-width);
        position: fixed;
        top: 0;
        left: 0;
        background-color: var(--bg-sidebar);
        color: var(--text-dark);
        padding: 20px 15px;
        z-index: 1000;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-right: 1px solid rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .brand {
        display: flex;
        align-items: center;
        padding: 15px;
        margin-bottom: 30px;
        text-decoration: none;
    }

    .brand-icon {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, var(--primary-accent), #3f37c9);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.2rem;
        margin-right: 12px;
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    .brand-text {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-dark);
        letter-spacing: -0.5px;
    }

    .nav-section-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        padding: 10px 15px;
        margin-top: 10px;
    }

    .nav-link {
        color: var(--text-muted) !important;
        padding: 12px 15px;
        margin: 4px 0;
        display: flex;
        align-items: center;
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.2s;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .nav-link i {
        font-size: 1.1rem;
        margin-right: 12px;
        width: 24px;
        transition: transform 0.2s;
    }

    .nav-link:hover {
        color: var(--primary-accent) !important;
        background-color: var(--nav-hover-bg);
    }

    .nav-link:hover i {
        transform: translateX(3px);
    }

    /* State Active */
    .nav-link.active {
        color: white !important;
        background: var(--primary-accent);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
    }

    .nav-link.active i {
        color: white;
    }

    /* Logout Style */
    .logout-section {
        margin-top: auto;
        padding-top: 20px;
        border-top: 1px solid rgba(0,0,0,0.05);
    }

    .btn-logout {
        color: #ef4444 !important;
        background-color: rgba(239, 68, 68, 0.05);
    }

    .btn-logout:hover {
        background-color: #ef4444 !important;
        color: white !important;
    }
</style>

<div class="sidebar">
    <a href="beranda.php" class="brand">
        <div class="brand-icon">
            <i class="fas fa-book-reader"></i>
        </div>
        <span class="brand-text">E-LIBRARY</span>
    </a>

    <div class="nav-section-label">Main Menu</div>
    <nav class="nav flex-column">
        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'beranda.php') ? 'active' : '' ?>" href="beranda.php">
            <i class="fas fa-grid-2"></i> Dashboard
        </a>
        
        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'buku.php') ? 'active' : '' ?>" href="buku.php">
            <i class="fas fa-book"></i> Data Buku
        </a>
        
        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'anggota.php') ? 'active' : '' ?>" href="anggota.php">
            <i class="fas fa-user-group"></i> Anggota
        </a>
        
        <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'peminjaman.php') ? 'active' : '' ?>" href="peminjaman.php">
            <i class="fas fa-receipt"></i> Peminjaman
        </a>
    </nav>

    <div class="logout-section">
        <a class="nav-link btn-logout" href="logout.php">
            <i class="fas fa-arrow-right-from-bracket"></i> Keluar
        </a>
    </div>
</div>