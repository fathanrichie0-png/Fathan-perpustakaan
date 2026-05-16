<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Admin | LibSpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .register-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            color: white;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white !important;
            border-radius: 12px;
            padding: 12px 15px;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #4361ee;
            box-shadow: none;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }
        .btn-register {
            background: #4361ee;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: 0.3s;
            margin-top: 10px;
        }
        .btn-register:hover {
            background: #304fd0;
            transform: translateY(-2px);
        }
        .login-link {
            color: #8db5ff;
            text-decoration: none;
            transition: 0.3s;
        }
        .login-link:hover {
            color: white;
        }
    </style>
</head>
<body>

<div class="register-card">
    <div class="text-center mb-4">
        <h3 class="fw-bold">Buat Akun Admin</h3>
        <p class="small text-white-50">Daftarkan identitas petugas untuk mengelola perpustakaan.</p>
    </div>

    <form action="proses_register.php" method="POST">
        <div class="mb-3">
            <label class="form-label small">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama petugas" required autocomplete="off">
        </div>

        <div class="mb-3">
            <label class="form-label small">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Username unik" required autocomplete="off">
        </div>

        <div class="mb-3">
            <label class="form-label small">Password</label>
            <input type="password" name="password" class="form-control" placeholder="******" required>
        </div>

        <div class="mb-4">
            <label class="form-label small">Konfirmasi Password</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="******" required>
        </div>

        <button type="submit" name="register" class="btn btn-primary w-100 btn-register mb-3">
            Daftar Sekarang
        </button>

        <div class="text-center">
            <p class="small text-white-50">Sudah punya akun? <a href="login.php" class="login-link">Login di sini</a></p>
        </div>
    </form>
</div>

</body>
</html>