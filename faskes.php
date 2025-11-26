<?php
session_start();
include 'koneksi.php';

// Pastikan user login
if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$msg = "";

// DELETE RATING 
if (isset($_POST['delete_rating'])) {
    $rating_id = $_POST['rating_id'];
    $q_del = "DELETE FROM faskes_ratings WHERE id = '$rating_id' AND user_id = '$user_id'";
    if (mysqli_query($koneksi, $q_del)) {
        echo "<script>alert('Rating berhasil dihapus!'); window.location='faskes.php';</script>";
    }
}

//LOGIKA TAMBAH RATING
if (isset($_POST['submit_rating'])) {
    $faskes_id = $_POST['faskes_id'];
    $score = $_POST['rating_score'];
    $review = mysqli_real_escape_string($koneksi, $_POST['review']);

    $cek = mysqli_query($koneksi, "SELECT id FROM faskes_ratings WHERE user_id='$user_id' AND faskes_id='$faskes_id'");
    
    if (mysqli_num_rows($cek) > 0) {
        $msg = "Anda sudah memberikan rating.";
    } else {
        $q_rate = "INSERT INTO faskes_ratings (user_id, faskes_id, rating, review) VALUES ('$user_id', '$faskes_id', '$score', '$review')";
        if (mysqli_query($koneksi, $q_rate)) {
            echo "<script>alert('Terima kasih atas penilaian Anda!'); window.location='faskes.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direktori Faskes - HealthCare+</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="education-body fade-in"> 

    <header class="education-header">
        <a href="index.php" class="back-btn">Kembali</a>
        <div class="header-title">
            <i class="fa-solid fa-hospital"></i>
            <h1>Direktori Fasilitas Kesehatan</h1>
        </div>

        <ul class="nav-menu" id="navMenu" style="text-align: right; margin-left: auto;">
                
                <li><a href="dailycheck.php">DailyCheck</a></li>
                <li><a href="education.php">Education</a></li>
        </ul>
    </header>

    <section class="faskes-grid">
        <?php
        // Ambil semua data
        $query = "SELECT f.*, 
                  (SELECT AVG(rating) FROM faskes_ratings WHERE faskes_id = f.id) as avg_rating,
                  (SELECT COUNT(*) FROM faskes_ratings WHERE faskes_id = f.id) as total_review
                  FROM faskes f ORDER BY f.nama ASC";
        
        $result = mysqli_query($koneksi, $query);

        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $faskes_id = $row['id'];
                $avg = number_format($row['avg_rating'], 1);
                
                $cek_my_rating = mysqli_query($koneksi, "SELECT * FROM faskes_ratings WHERE faskes_id='$faskes_id' AND user_id='$user_id'");
                $my_rating = mysqli_fetch_assoc($cek_my_rating);
        ?>
            <div class="faskes-card">
                <div class="faskes-body">
                    <span class="faskes-type"><?php echo $row['tipe']; ?></span>
                    <h3 class="faskes-name"><?php echo $row['nama']; ?></h3>
                    
                    <div class="faskes-info">
                        <i class="fa-solid fa-location-dot"></i>
                        <span><?php echo $row['alamat']; ?>, Kec. <?php echo $row['kecamatan']; ?></span>
                    </div>
                    <div class="faskes-info">
                        <i class="fa-solid fa-phone"></i>
                        <span><?php echo $row['kontak']; ?></span>
                    </div>
                    <div class="faskes-info">
                        <i class="fa-solid fa-star" style="color: #FFC107;"></i>
                        <span><?php echo ($avg > 0) ? "$avg / 5.0 ($row[total_review] ulasan)" : "Belum ada rating"; ?></span>
                    </div>
                </div>

                <div class="rating-section">
                    
                    
                    <?php if ($my_rating) { ?>
                        <div class="user-rating-display">
                            <p style="font-size: 13px; color: #666;">Rating Anda:</p>
                            <div class="rating-stars">
                                <?php for($i=1; $i<=5; $i++) echo ($i <= $my_rating['rating']) ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>'; ?>
                            </div>
                            <p style="font-size: 12px; font-style: italic;">"<?php echo $my_rating['review']; ?>"</p>
                            <form method="POST" onsubmit="return confirm('Hapus rating ini?')">
                                <input type="hidden" name="rating_id" value="<?php echo $my_rating['id']; ?>">
                                <button type="submit" name="delete_rating" class="btn-delete-rate"><i class="fa-solid fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    <?php } else { ?>
                        <form method="POST" class="rating-form">
                            <input type="hidden" name="faskes_id" value="<?php echo $faskes_id; ?>">
                            <div class="rating-input">
                                <select name="rating_score" class="rating-select" required>
                                    <option value="" disabled selected>⭐ Nilai</option>
                                    <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                    <option value="4">⭐⭐⭐⭐ (4)</option>
                                    <option value="3">⭐⭐⭐ (3)</option>
                                    <option value="2">⭐⭐ (2)</option>
                                    <option value="1">⭐ (1)</option>
                                </select>
                                <button type="submit" name="submit_rating" class="btn-rate">Kirim</button>
                            </div>
                            <input type="text" name="review" placeholder="Ulasan singkat..." style="width: 100%; padding: 8px; border:1px solid #ddd; border-radius: 5px; font-size: 12px;">
                        </form>
                    <?php } ?>
                </div>
            </div>
        <?php 
            }
        } else {
            echo "<p style='text-align:center; grid-column: 1/-1;'>Data fasilitas kesehatan tidak ditemukan.</p>";
        }
        ?>
    </section>

</body>
</html>