<?php
    include 'koneksi.php';
    session_start();

    if(!isset($_SESSION['login_user'])) {
        header('Location: login.php');
        exit;
    }

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthCare+ - Informasi Kesehatan Modern</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="assets/style.css">
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

    <section class="hero fade-in" id="beranda">
        <div class="hero-content">
            <p>Halo, <?php echo htmlspecialchars($_SESSION['fullname']); ?></p>
            <h1>Website Informasi Edukasi Kesehatan</h1>
            <p>Edukasi, statistik, dan fasilitas kesehatan dalam satu tempat.</p>
            <div class="hero-buttons">
                <a href="dailycheck.php" class="btn btn-green">Mulai Sekarang</a>
                <a href="#features" class="btn btn-blue">Pelajari Fitur</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="assets/img/3585182_66139.svg" alt="Hero Image">
        </div>
    </section>

    <section class="features" id="features">
        <div class="features-container">
            <h2 class="section-title">Fitur Unggulan Kami</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fa-solid fa-heart-pulse"></i>
                    <h3>Health Check-In</h3>
                    <p>Catat kondisi kesehatan Anda setiap hari dengan mudah dan dapatkan analisis yang akurat.</p>
                    <a href="dailycheck.php" class="btn btn-blue btn-small">Lihat Selengkapnya</a>
                </div>

                <div class="feature-card">
                    <i class="fa-solid fa-headphones"></i>
                    <h3>Audio Edukasi</h3>
                    <p>Dengarkan konten edukasi kesehatan dalam format audio yang praktis dan informatif.</p>
                    <a href="education.php" class="btn btn-blue btn-small">Lihat Selengkapnya</a>
                </div>

                <div class="feature-card">
                    <i class="fa-solid fa-hospital"></i>
                    <h3>Direktori Fasilitas Kesehatan</h3>
                    <p>Temukan rumah sakit, klinik, dan apotek terdekat dengan informasi lengkap dan terpercaya.</p>
                    <a href="faskes.php" class="btn btn-blue btn-small">Lihat Selengkapnya</a>
                </div>
            </div>
        </div>
    </section>

    <section class="statistics">
        <div class="stats-container">
            <h2 class="section-title">Statistik Kesehatan Indonesia</h2>
            <div class="chart-container">
                <canvas id="chartKesehatanPemuda"></canvas>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-container">
            <h3>HealthCare+</h3>
            <p>Platform informasi kesehatan terpercaya untuk Indonesia yang lebih sehat</p>
            <p class="copyright">&copy; 2025 HealthCare+. All rights reserved.</p>
        </div>
    </footer>

    <script src="assets/script.js"></script>
</body>
</html>