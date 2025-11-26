<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['login_user'])){
    header("Location: login.php");
    exit;
}

?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio Edukasi - HealthCare+</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> 
</head>

<body class="education-body fade-in"> 

    

    <header class="education-header">
        <a href="index.php" class="back-btn">Kembali</a> 
        <h1 style="margin: 0; font-size: 22px;">🎧 Audio Edukasi Kesehatan</h1>
    </header>

    <section class="education-content">
        <h2 class="section-subtitle">Dengarkan, Belajar, dan Tingkatkan Kesehatan Anda</h2>

        <div class="audio-grid">
            
            <div class="audio-card fade-in">
                <i class="fa-solid fa-headset audio-icon"></i>
                <h3 class="audio-title">1. Mengenal Gejala Burnout</h3>
                <p class="audio-duration">Durasi: 5 menit 12 detik</p>
                <audio controls class="audio-player">
                    <source src="assets/audio/burnout_gejala.mp3" type="audio/mpeg">
                    Browser Anda tidak mendukung elemen audio.
                </audio>
            </div>

            <div class="audio-card fade-in" style="animation-delay: 0.1s;">
                <i class="fa-solid fa-headset audio-icon"></i>
                <h3 class="audio-title">2. Rahasia Tidur Nyenyak 8 Jam</h3>
                <p class="audio-duration">Durasi: 7 menit 45 detik</p>
                <audio controls class="audio-player">
                    <source src="assets/audio/tidur_nyenyak.mp3" type="audio/mpeg">
                    Browser Anda tidak mendukung elemen audio.
                </audio>
            </div>

            <div class="audio-card fade-in" style="animation-delay: 0.2s;">
                <i class="fa-solid fa-headset audio-icon"></i>
                <h3 class="audio-title">3. Pentingnya Vitamin D Harian</h3>
                <p class="audio-duration">Durasi: 4 menit 30 detik</p>
                <audio controls class="audio-player">
                    <source src="assets/audio/vitamin_d.mp3" type="audio/mpeg">
                    Browser Anda tidak mendukung elemen audio.
                </audio>
            </div>

            <div class="audio-card fade-in" style="animation-delay: 0.3s;">
                <i class="fa-solid fa-headset audio-icon"></i>
                <h3 class="audio-title">4. Tips Mengelola Stres Kerja</h3>
                <p class="audio-duration">Durasi: 6 menit 05 detik</p>
                <audio controls class="audio-player">
                    <source src="assets/audio/stres_kerja.mp3" type="audio/mpeg">
                    Browser Anda tidak mendukung elemen audio.
                </audio>
            </div>

        </div>
    </section>

</body>
</html>
