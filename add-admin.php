<?php
session_start();
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Admin Baru - HealthCare+</title>
    <link rel="stylesheet" href="assets/style.css?">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="nav-logo">HealthCare+</a>
            <button class="nav-toggle" onclick="toggleMobileMenu()">☰</button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php">Beranda</a></li>
                <li><a href="dailycheck.php">DailyCheck</a></li>
                <li><a href="education.php">Education</a></li>
                <li><a href="faskes.php">Faskes</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="admin-container fade-in">

        <div class="admin-header">
            <h1><i class="fas fa-user-plus"></i> Tambah Admin Baru</h1>
            <p>Buat akun admin baru dengan hak akses penuh.</p>
        </div>

        <div class="edit-card">
            <form action="store_admin.php" method="POST">

                <div class="form-group">
                    <label>Username Admin</label>
                    <input type="text" name="username" required placeholder="Masukkan username admin">
                </div>

                <div class="form-group">
                    <label>Email Admin</label>
                    <input type="email" name="email" required placeholder="Masukkan email admin">
                </div>

                <div class="form-group">
                    <label>Password Admin</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter">
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirm" required placeholder="Ulangi password">
                </div>

                <input type="hidden" name="role" value="admin">

                <div class="form-actions">
                    <button type="submit" class="btn-primary btn-login-reg">
                        <i class="fas fa-save"></i> Buat Admin
                    </button>

                    <a href="admin-dashboard.php" class="btn-cancel">
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
