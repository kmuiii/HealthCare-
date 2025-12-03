<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: admin-dashboard.php");
    exit;
}

$id = intval($_GET['id']);

$query = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($koneksi, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: admin-dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - HealthCare+</title>
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
            <h1><i class="fas fa-user-edit"></i> Edit User</h1>
            <p>Perbarui informasi pengguna</p>
        </div>

        <div class="edit-card">
            <form action="update-user.php" method="POST">

                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Password Baru (opsional)</label>
                    <input type="password" name="password" placeholder="Biarkan kosong jika tidak diganti">
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirm" placeholder="Ulangi password">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary btn-login-reg">
                        <i class="fas fa-save"></i> Simpan Perubahan
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
