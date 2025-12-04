<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Fasilitas Kesehatan - HealthCare+</title>

    <link rel="stylesheet" href="assets/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="admin-dashboard.php" class="nav-logo">HealthCare+ Admin</a>
            <button class="nav-toggle" onclick="toggleMobileMenu()">☰</button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php">Lihat Website</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="admin-container fade-in">

        <div class="admin-header">
            <h1><i class="fas fa-hospital"></i> Tambah Fasilitas Kesehatan</h1>
            <p>Masukkan data fasilitas kesehatan baru untuk ditampilkan ke pengguna.</p>
        </div>

        <div class="edit-card">
            <form action="store_faskes.php" method="POST">

                <div class="form-group">
                    <label>Nama Fasilitas</label>
                    <input type="text" name="nama" required placeholder="Contoh: RS Sinar Sehat">
                </div>

                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <label>Tipe Fasilitas</label>
                            <select name="tipe" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="Rumah Sakit">Rumah Sakit</option>
                                <option value="Klinik">Klinik</option>
                                <option value="Puskesmas">Puskesmas</option>
                                <option value="Apotek">Apotek</option>
                            </select>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label>Kecamatan</label>
                            <input type="text" name="kecamatan" required placeholder="Contoh: Purwokerto Selatan">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <input type="text" name="alamat" required placeholder="Masukkan alamat lengkap fasilitas">
                </div>

                <div class="form-group">
                    <label>Kontak (Nomor Telepon / WA)</label>
                    <input type="text" name="kontak" required placeholder="Contoh: 0812-3456-7890">
                </div>

                <div class="form-actions" style="display:flex; gap:12px; margin-top:20px;">
                    <button type="submit" class="btn-primary btn-login-reg">
                        <i class="fas fa-save"></i> Simpan Fasilitas
                    </button>

                    <a href="admin-dashboard.php" class="btn-cancel" style="text-decoration:none;">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
    <footer class="footer">
        <div class="footer-container">
            <h3>HealthCare+ Admin</h3>
            <p>Platform informasi kesehatan terpercaya untuk Indonesia yang lebih sehat</p>
            <p class="copyright">&copy; 2025 HealthCare+. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
