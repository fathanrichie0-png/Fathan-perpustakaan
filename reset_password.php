<?php
session_start();

// Keamanan: Jika tidak ada session dari lupa_password.php, tendang kembali
if (!isset($_SESSION['reset_username'])) {
    header("Location: lupa_password.php");
    exit();
}

require_once 'config/koneksi.php';
$user_target = $_SESSION['reset_username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Set Password Baru | LibSpace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0f172a;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .reset-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .form-label { font-size: 0.85rem; font-weight: 600; color: #475569; }
        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }
        .form-control:focus {
            background: #ffffff;
            border-color: #4361ee;
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }
        .btn-save {
            background: #4361ee;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            color: white;
            transition: 0.3s;
            margin-top: 10px;
        }
        .btn-save:hover {
            background: #304fd0;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(67, 97, 238, 0.3);
        }
        .username-badge {
            background: #eff6ff;
            color: #1e40af;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="reset-card">
    <div class="text-center mb-4">
        <h4 class="fw-bold text-dark mb-2">Password Baru</h4>
        <p class="text-muted small">Menyetel ulang password untuk akun:</p>
        <span class="username-badge"><i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($user_target) ?></span>
    </div>

    <form action="" method="POST">
        <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <div class="input-group">
                <input type="password" name="pass1" id="p1" class="form-control" required placeholder="Minimal 6 karakter">
                <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePass('p1')" style="border-radius: 0 12px 12px 0; border: 1px solid #e2e8f0; background: #f8fafc;">
                    <i class="fas fa-eye" id="icon_p1"></i>
                </button>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Konfirmasi Password</label>
            <div class="input-group">
                <input type="password" name="pass2" id="p2" class="form-control" required placeholder="Ulangi password baru">
                <button class="btn btn-outline-secondary border-start-0" type="button" onclick="togglePass('p2')" style="border-radius: 0 12px 12px 0; border: 1px solid #e2e8f0; background: #f8fafc;">
                    <i class="fas fa-eye" id="icon_p2"></i>
                </button>
            </div>
        </div>

        <button type="submit" name="update" class="btn btn-save w-100 shadow-sm">
            Simpan & Perbarui Password
        </button>
    </form>
</div>

<script>
    // Fungsi untuk mengintip password
    function togglePass(id) {
        const x = document.getElementById(id);
        const icon = document.getElementById('icon_' + id);
        if (x.type === "password") {
            x.type = "text";
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            x.type = "password";
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

<?php
if (isset($_POST['update'])) {
    $p1 = $_POST['pass1'];
    $p2 = $_POST['pass2'];

    // 1. Validasi Panjang Password
    if (strlen($p1) < 6) {
        echo "<script>
            Swal.fire({
                icon: 'warning',
                title: 'Terlalu Pendek',
                text: 'Password minimal harus 6 karakter!',
                confirmButtonColor: '#4361ee'
            });
        </script>";
    } 
    // 2. Validasi Kesesuaian
    else if ($p1 !== $p2) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Tidak Cocok',
                text: 'Konfirmasi password tidak sesuai!',
                confirmButtonColor: '#4361ee'
            });
        </script>";
    } 
    // 3. Proses Update
    else {
        // Gunakan md5 sesuai dengan sistem login Anda yang lama
        $password_baru = md5($p1); 

        $query_update = mysqli_query($conn, "UPDATE admin SET password = '$password_baru' WHERE username = '$user_target'");

        if ($query_update) {
            // Hapus session agar tidak bisa back ke halaman ini
            unset($_SESSION['reset_username']);
            session_destroy();

            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Password Anda telah diperbarui. Silakan login kembali.',
                    confirmButtonColor: '#4361ee'
                }).then(() => {
                    window.location.href = 'login.php';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Sistem Error',
                    text: 'Gagal memperbarui database.',
                    confirmButtonColor: '#4361ee'
                });
            </script>";
        }
    }
}
?>

</body>
</html>