<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: admin-dashboard.php");
    exit;
}

if (!isset($_SESSION['login_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

$query = "SELECT * FROM faskes WHERE id = $id";
$result = mysqli_query($koneksi, $query);
$faskes = mysqli_fetch_assoc($result);

if (!$faskes) {
    header("Location: admin-dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Fasilitas Kesehatan - HealthCare+</title>
    <link rel="stylesheet" href="assets/style.css">
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
            <h1><i class="fas fa-hospital"></i> Edit Fasilitas</h1>
            <p>Perbarui informasi fasilitas kesehatan</p>
        </div>

        <div class="edit-card">
            <form action="update-faskes.php" method="POST">

                <input type="hidden" name="id" value="<?php echo $faskes['id']; ?>">

                <div class="form-group">
                    <label>Nama Fasilitas</label>
                    <input type="text" name="nama" value="<?php echo htmlspecialchars($faskes['nama']); ?>" required>
                </div>

                <div class="form-row">
                    <div class="col">
                        <div class="form-group">
                            <label>Tipe</label>
                            <select name="tipe" required>
                                <?php 
                                    $types = ["Rumah Sakit","Klinik","Puskesmas","Apotek"];
                                    foreach ($types as $t) {
                                        $sel = ($faskes['tipe']==$t) ? "selected" : "";
                                        echo "<option value='$t' $sel>$t</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label>Kecamatan</label>
                            <input type="text" name="kecamatan" value="<?php echo htmlspecialchars($faskes['kecamatan']); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <input type="text" name="alamat" value="<?php echo htmlspecialchars($faskes['alamat']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Kontak</label>
                    <input type="text" name="kontak" value="<?php echo htmlspecialchars($faskes['kontak']); ?>" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary btn-login-reg">
                        <i class="fas fa-save"></i> Simpan Perubahan
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
