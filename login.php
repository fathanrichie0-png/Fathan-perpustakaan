<?php
session_start();
if (isset($_SESSION["status"])) {
    header("Location: beranda.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "perpus2";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | E-Library</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Cormorant+Garamond:wght@500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-deep: #0f1720;
            --panel: rgba(231, 238, 246, 0.14);
            --panel-strong: rgba(255, 255, 255, 0.18);
            --line: rgba(255, 255, 255, 0.22);
            --text-main: #f7f9fc;
            --text-soft: rgba(236, 241, 247, 0.72);
            --accent: #7ca8d6;
            --accent-deep: #5a88bb;
            --shadow: 0 30px 80px rgba(4, 10, 18, 0.45);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-main);
            background-color: var(--bg-deep);
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background:
                linear-gradient(120deg, rgba(9, 16, 24, 0.9), rgba(17, 24, 36, 0.58)),
                url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1600&q=80') center center / cover no-repeat;
            transform: scale(1.08);
            filter: blur(12px);
            z-index: -3;
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            background:
                radial-gradient(circle at top left, rgba(124, 168, 214, 0.25), transparent 34%),
                radial-gradient(circle at bottom right, rgba(255, 255, 255, 0.12), transparent 28%),
                linear-gradient(135deg, rgba(15, 23, 32, 0.18), rgba(15, 23, 32, 0.7));
            z-index: -2;
        }

        .page-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
            position: relative;
        }

        .page-shell::before,
        .page-shell::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .page-shell::before {
            width: 280px;
            height: 280px;
            top: 7%;
            left: 6%;
        }

        .page-shell::after {
            width: 220px;
            height: 220px;
            right: 10%;
            bottom: 8%;
        }

        .login-stage {
            width: min(1200px, 100%);
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            align-items: stretch;
            gap: 36px;
            position: relative;
            z-index: 1;
        }

        .hero-panel {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 28px 18px 28px 8px;
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-radius: 999px;
            width: fit-content;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.16);
        }

        .brand-mark span {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(124, 168, 214, 0.9), rgba(90, 136, 187, 0.7));
            color: #fff;
            font-size: 1rem;
        }

        .brand-mark strong {
            letter-spacing: 0.08em;
            font-size: 0.82rem;
            color: var(--text-soft);
            text-transform: uppercase;
        }

        .hero-copy {
            max-width: 560px;
            padding-top: 24px;
        }

        .eyebrow {
            display: inline-block;
            margin-bottom: 18px;
            color: rgba(231, 238, 246, 0.78);
            font-size: 0.85rem;
            letter-spacing: 0.28em;
            text-transform: uppercase;
        }

        .hero-copy h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(3rem, 5vw, 5.3rem);
            line-height: 0.93;
            font-weight: 600;
            letter-spacing: -0.02em;
            margin-bottom: 20px;
        }

        .hero-copy p {
            max-width: 500px;
            color: var(--text-soft);
            font-size: 1.02rem;
            line-height: 1.8;
            margin-bottom: 28px;
        }

        .hero-notes {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .hero-note {
            min-width: 160px;
            padding: 16px 18px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            box-shadow: 0 18px 35px rgba(0, 0, 0, 0.14);
        }

        .hero-note small {
            display: block;
            color: rgba(236, 241, 247, 0.62);
            font-size: 0.76rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .hero-note strong {
            font-size: 0.98rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .reader-visual {
            position: relative;
            margin-top: 36px;
            min-height: 430px;
            display: flex;
            align-items: flex-end;
            justify-content: flex-start;
        }

        .reader-glow {
            position: absolute;
            inset: auto auto 8% 8%;
            width: 340px;
            height: 340px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(124, 168, 214, 0.28), rgba(124, 168, 214, 0.02) 70%, transparent 72%);
            filter: blur(10px);
        }

        .reader-card {
            position: relative;
            width: min(500px, 100%);
            padding: 22px;
            border-radius: 30px;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.14), rgba(255, 255, 255, 0.05));
            border: 1px solid rgba(255, 255, 255, 0.16);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .reader-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.12), transparent 38%);
            pointer-events: none;
        }

        .reader-card img {
            width: 100%;
            height: 100%;
            min-height: 420px;
            object-fit: cover;
            border-radius: 22px;
            display: block;
            filter: saturate(0.88) contrast(1.04) brightness(0.93);
        }

        .reader-caption {
            position: absolute;
            left: 40px;
            right: 40px;
            bottom: 38px;
            padding: 18px 20px;
            border-radius: 20px;
            background: rgba(10, 18, 28, 0.42);
            border: 1px solid rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        .reader-caption strong {
            display: block;
            font-size: 1rem;
            margin-bottom: 6px;
        }

        .reader-caption span {
            color: rgba(236, 241, 247, 0.74);
            font-size: 0.92rem;
            line-height: 1.6;
        }

        .login-panel {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: min(430px, 100%);
            padding: 34px;
            border-radius: 32px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.17), rgba(255, 255, 255, 0.08));
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.16), transparent 45%, rgba(124, 168, 214, 0.12));
            pointer-events: none;
        }

        .login-card > * {
            position: relative;
            z-index: 1;
        }

        .login-head {
            margin-bottom: 28px;
        }

        .login-head .badge-label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            padding: 8px 14px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-soft);
            font-size: 0.77rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .login-head h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.6rem;
            line-height: 1;
            margin-bottom: 12px;
        }

        .login-head p {
            color: var(--text-soft);
            line-height: 1.7;
            margin-bottom: 0;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 16px;
        }

        .input-group-custom label {
            display: block;
            margin-bottom: 10px;
            font-size: 0.86rem;
            color: rgba(244, 247, 250, 0.78);
            letter-spacing: 0.04em;
        }

        .field-wrap {
            position: relative;
        }

        .field-wrap i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.58);
        }

        .input-group-custom input {
            width: 100%;
            height: 56px;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: rgba(247, 249, 252, 0.08);
            color: var(--text-main);
            outline: none;
            padding: 0 18px 0 48px;
            transition: border-color 0.25s ease, background 0.25s ease, box-shadow 0.25s ease;
        }

        .input-group-custom input::placeholder {
            color: rgba(236, 241, 247, 0.45);
        }

        .input-group-custom input:focus {
            border-color: rgba(124, 168, 214, 0.8);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 4px rgba(124, 168, 214, 0.14);
        }

        .form-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin: 8px 0 26px;
            color: var(--text-soft);
            font-size: 0.86rem;
        }

        .remember {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .remember input {
            width: 16px;
            height: 16px;
            accent-color: var(--accent);
        }

        .form-meta a {
            color: rgba(240, 245, 250, 0.82);
            text-decoration: none;
        }

        .form-meta a:hover {
            color: #fff;
        }

        .btn-submit {
            width: 100%;
            height: 56px;
            border: none;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--accent), var(--accent-deep));
            color: #fff;
            font-weight: 700;
            letter-spacing: 0.03em;
            box-shadow: 0 18px 30px rgba(90, 136, 187, 0.35);
            transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 24px 34px rgba(90, 136, 187, 0.42);
            filter: brightness(1.02);
        }

        .register-link {
            margin-top: 24px;
            text-align: center;
            color: var(--text-soft);
            font-size: 0.92rem;
        }

        .register-link a {
            color: #fff;
            text-decoration: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            padding-bottom: 2px;
        }

        .register-link a:hover {
            border-bottom-color: rgba(255, 255, 255, 0.7);
        }

        .login-footer {
            margin-top: 26px;
            padding-top: 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(236, 241, 247, 0.62);
            font-size: 0.81rem;
            line-height: 1.7;
            text-align: center;
        }

        @media (max-width: 991.98px) {
            .login-stage {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .hero-panel {
                padding: 0;
            }

            .hero-copy {
                max-width: none;
            }

            .reader-visual {
                min-height: auto;
                margin-top: 24px;
            }

            .reader-card {
                max-width: 640px;
            }

            .login-panel {
                justify-content: flex-start;
            }
        }

        @media (max-width: 767.98px) {
            .page-shell {
                padding: 18px;
            }

            .hero-panel {
                display: none;
            }

            .login-stage {
                grid-template-columns: 1fr;
            }

            .login-card {
                padding: 26px 22px;
                border-radius: 26px;
            }

            .login-head h2 {
                font-size: 2.2rem;
            }

            .form-meta {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <main class="page-shell">
        <section class="login-stage">
            <div class="hero-panel">
                <div class="brand-mark">
                    <span><i class="fas fa-book-open-reader"></i></span>
                    <strong>E-Library Portal</strong>
                </div>

                <div class="hero-copy">
                    <span class="eyebrow">Quiet knowledge space</span>
                    <h1>Membaca, menata, dan menjaga ritme perpustakaan.</h1>
                    <p>
                        Tampilan login ini dirancang dengan nuansa glassmorphism yang tenang, ringan, dan intelektual.
                        Latar perpustakaan yang blur memberi kedalaman, sementara aksen biru lembut menjaga karakter visual tetap modern.
                    </p>

                    <div class="hero-notes">
                        <div class="hero-note">
                            <small>Atmosfer</small>
                            <strong>Hening, fokus, modern</strong>
                        </div>
                        <div class="hero-note">
                            <small>Material</small>
                            <strong>Frosted glass berlapis</strong>
                        </div>
                        <div class="hero-note">
                            <small>Aksen</small>
                            <strong>Biru rak buku yang halus</strong>
                        </div>
                    </div>
                </div>

                <div class="reader-visual">
                    <div class="reader-glow"></div>
                    <div class="reader-card">
                        <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=900&q=80" alt="Seseorang sedang membaca buku di perpustakaan">
                        <div class="reader-caption">
                            <strong>Ruang baca yang terasa personal</strong>
                            <span>Ilustrasi visual menonjol untuk memperkuat kesan reflektif dan tenang sebelum masuk ke sistem.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="login-panel">
                <div class="login-card">
                    <div class="login-head">
                        <div class="badge-label">
                            <i class="fas fa-shield-halved"></i>
                            Akses petugas
                        </div>
                        <h2>Login</h2>
                        <p>Masuk untuk mengelola koleksi, anggota, dan aktivitas perpustakaan dalam satu ruang kerja yang rapi.</p>
                    </div>

                    <form action="proses_login.php" method="POST">
                        <div class="input-group-custom">
                            <label for="username">Username</label>
                            <div class="field-wrap">
                                <i class="fas fa-user"></i>
                                <input type="text" id="username" name="username" placeholder="Masukkan username" required>
                            </div>
                        </div>

                        <div class="input-group-custom">
                            <label for="password">Password</label>
                            <div class="field-wrap">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                            </div>
                        </div>

                        <div class="form-meta">
                            <label class="remember"><input type="checkbox" name="remember"> Simpan sesi</label>
                            <a href="lupa_password.php">Lupa password</a>
                        </div>

                        <button type="submit" name="login" class="btn-submit">Masuk ke Dashboard</button>

                                            <div class="register-link">
                            <a href="../siswa/login_siswa.php"> Login siswa</a>
                        </div>

                        <div class="register-link">
                            Belum punya akun? <a href="register.php">Daftar sekarang</a>
                        </div>
                    </form>

                    <div class="login-footer">
                </div>
            </div>
        </section>
    </main>
</body>
</html>
