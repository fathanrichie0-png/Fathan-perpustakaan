<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Siswa | E-Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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

    <div class="max-w-xl w-full">
        <div class="glass rounded-[40px] p-8 md:p-12 text-white shadow-2xl">
            
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600/20 rounded-2xl mb-4 border border-blue-500/30">
                    <i class="fas fa-user-plus text-2xl text-blue-400"></i>
                </div>
                <h1 class="serif text-3xl mb-2">Daftar Akun Baru</h1>
                <p class="text-gray-400 text-sm tracking-wide">Lengkapi data diri untuk akses perpustakaan digital</p>
            </div>

            <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
                <div class="mb-6 p-4 rounded-2xl bg-red-500/20 border border-red-500/50 text-red-200 text-xs text-center">
                    <i class="fas fa-exclamation-circle mr-2"></i> Username atau NISN sudah terdaftar!
                </div>
            <?php endif; ?>

            <form action="proses_daftar.php" method="POST" class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Masukkan nama sesuai kartu siswa" required 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-sm">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Username / NISN</label>
                        <input type="text" name="username" placeholder="Contoh: 12345" required 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Kelas</label>
                        <input type="text" name="kelas" placeholder="Contoh: XII RPL 1" required 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest text-gray-400 mb-2">Password</label>
                    <input type="password" name="password" placeholder="Buat password aman" required 
                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all text-sm">
                </div>

                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-600/30 transition-all transform hover:scale-[1.02] active:scale-95 mt-4">
                    BUAT AKUN SEKARANG
                </button>
            </form>

            <div class="mt-10 text-center">
                <p class="text-sm text-gray-400">
                    Sudah punya akun? 
                    <a href="login_siswa.php" class="text-white font-bold hover:text-blue-400 transition-colors ml-1">Login di sini</a>
                </p>
            </div>
        </div>
        
        <p class="text-center text-gray-500 text-[10px] mt-8 uppercase tracking-[0.2em]">© 2026 LibSpace Digital Ecosystem</p>
    </div>

</body>
</html>