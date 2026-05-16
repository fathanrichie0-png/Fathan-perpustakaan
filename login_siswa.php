<?php
session_start();
// Jika sudah login, langsung lempar ke beranda
if (isset($_SESSION['status']) && $_SESSION['status'] == "login_siswa") {
    header("location:beranda_siswa.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Siswa | E-Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&q=80&w=2000');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
        }
        .serif { font-family: 'Playfair Display', serif; }
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        input::placeholder { color: rgba(255, 255, 255, 0.4); }
    </style>
</head>
<body class="flex items-center justify-center p-6">

    <div class="max-w-md w-full">
        <div class="glass rounded-[40px] p-10 text-white shadow-2xl transition-all">
            
            <div class="text-center mb-8">
                <h1 class="serif text-4xl mb-2">E-Library</h1>
                <p class="text-gray-400 text-sm tracking-wide">Selamat datang kembali, Siswa!</p>
            </div>

            <?php 
            if(isset($_GET['pesan'])){
                $msg = "";
                $bg = "bg-red-500/20 border-red-500/50 text-red-200";
                
                if($_GET['pesan'] == "gagal"){
                    $msg = "Username atau Password salah!";
                } else if($_GET['pesan'] == "logout"){
                    $msg = "Berhasil keluar akun.";
                    $bg = "bg-green-500/20 border-green-500/50 text-green-200";
                } else if($_GET['pesan'] == "belum_login"){
                    $msg = "Silakan login terlebih dahulu.";
                    $bg = "bg-yellow-500/20 border-yellow-500/50 text-yellow-200";
                }
                
                if($msg != "") {
                    echo "<div class='mb-6 p-4 rounded-2xl border $bg text-xs text-center'>$msg</div>";
                }
            }
            ?>

            <form action="cek_login_siswa.php" method="post" autocomplete="off" class="space-y-6">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Username / NISN</label>
                    <input type="text" name="username" placeholder="Masukkan username" required 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-sm">
                </div>
                
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Password</label>
                    <input type="password" name="password" placeholder="••••••••" required 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-sm text-white">
                </div>

                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-600/30 transition-all transform hover:scale-[1.02] active:scale-95">
                    MASUK KE PERPUSTAKAAN
                </button>
            </form>

            <div class="mt-10 text-center space-y-4">
                <p class="text-sm text-gray-400">
                    Belum punya akun? 
                    <a href="daftar.php" class="text-white font-bold hover:text-blue-400 transition-colors ml-1">Daftar Sekarang</a>
                </p>
                
                <div class="pt-4 border-t border-white/10">
                    <a href="../admin/login.php" class="text-xs text-gray-500 hover:text-gray-300 transition-colors uppercase tracking-widest flex items-center justify-center gap-2">
                        <span>&larr;</span> Login sebagai Admin
                    </a>
                </div>
            </div>
        </div>
        
        <p class="text-center text-gray-500 text-[10px] mt-8 uppercase tracking-[0.2em]">© 2026 LibSpace Digital Ecosystem</p>
    </div>

</body>
</html>