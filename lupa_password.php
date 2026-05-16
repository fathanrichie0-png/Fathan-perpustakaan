<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password | LibSpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover; background-position: center; height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 24px; padding: 40px; width: 100%; max-width: 420px; color: white;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);
            color: white !important; border-radius: 12px; padding: 12px 15px;
        }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.5); }
        .form-control:focus { background: rgba(255, 255, 255, 0.2); box-shadow: none; border-color: #4361ee; }
        .btn-verify { background: #4361ee; border: none; border-radius: 12px; padding: 12px; font-weight: 700; transition: 0.3s; }
        .btn-verify:hover { background: #304fd0; transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="glass-card shadow-lg">
    <div class="text-center mb-4">
        <div class="mb-3"><i class="fas fa-shield-alt fa-3x text-primary"></i></div>
        <h3 class="fw-bold">Pemulihan Akun</h3>
        <p class="text-white-50 small">Masukkan username petugas Anda untuk memverifikasi identitas.</p>
    </div>

    <form action="" method="POST">
        <div class="mb-4">
            <label class="form-label small fw-bold">Username</label>
            <div class="input-group">
                <span class="input-group-text bg-transparent border-0 text-white"><i class="fas fa-user"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username Anda" required autocomplete="off">
            </div>
        </div>
        
        <button type="submit" name="verify" class="btn btn-primary w-100 btn-verify mb-3 shadow">
            Verifikasi Akun
        </button>
        
        <div class="text-center">
            <a href="login.php" class="text-decoration-none small text-white-50 hover-white">Kembali ke Login</a>
        </div>
    </form>
</div>

<?php
if (isset($_POST['verify'])) {
    require_once 'config/koneksi.php';
    session_start();
    
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username'");

    if (mysqli_num_rows($query) > 0) {
        $_SESSION['reset_username'] = $username;
        echo "<script>
            Swal.fire({
                icon: 'success', title: 'User Ditemukan', text: 'Silakan atur password baru Anda.',
                confirmButtonColor: '#4361ee'
            }).then(() => { window.location.href='reset_password.php'; });
        </script>";
    } else {
        echo "<script>
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Username tidak terdaftar di sistem!', confirmButtonColor: '#4361ee' });
        </script>";
    }
}
?>
</body>
</html>