<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Siswa | E-Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&q=80&w=2000');
            background-size: cover; background-position: center; min-height: 100vh;
        }
        .serif { font-family: 'Playfair Display', serif; }
        .glass { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="flex items-center justify-center p-6">
    <div class="max-w-5xl w-full grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div class="text-white space-y-6 hidden md:block">
            <h1 class="serif text-6xl leading-tight">Membaca adalah <br>jendela dunia.</h1>
            <p class="text-gray-300 text-lg">Daftarkan diri Anda untuk mendapatkan akses penuh ke koleksi perpustakaan kami secara digital maupun fisik.</p>
            <div class="flex gap-4">
                <div class="glass p-4 rounded-2xl text-center flex-1">
                    <p class="text-xs text-gray-400 uppercase">Status</p>
                    <p class="font-bold">Keanggotaan Aktif</p>
                </div>
                <div class="glass p-4 rounded-2xl text-center flex-1">
                    <p class="text-xs text-gray-400 uppercase">Akses</p>
                    <p class="font-bold">Digital & Fisik</p>
                </div>
            </div>
        </div>

        <div class="glass rounded-3xl p-10 text-white shadow-2xl">
            <h2 class="serif text-3xl mb-2">Pendaftaran Siswa</h2>
            <p class="text-gray-400 text-sm mb-8">Gunakan NIS resmi Anda untuk mendaftar.</p>
            
            <form action="proses.php?action=create" method="POST" class="space-y-5">
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2">Nomor Induk Siswa (NIS)</label>
                    <input type="text" name="nis" required placeholder="Contoh: 2026002"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_siswa" required placeholder="Masukkan nama Anda"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-widest text-gray-400 mb-2">Kelas</label>
                    <input type="text" name="kelas" required placeholder="Contoh: XI-RPL 2"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 py-4 rounded-xl font-bold shadow-lg shadow-blue-600/30 transition-all transform hover:scale-[1.01]">
                    Daftar Sekarang
                </button>
            </form>
            <p class="text-center mt-6 text-sm text-gray-400">
                Sudah terdaftar? <a href="login_siswa.php" class="text-blue-400 hover:underline">Lihat Data</a>
            </p>
        </div>
    </div>
</body>
</html>