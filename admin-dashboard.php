<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];
    $delete_query = "DELETE FROM users WHERE id = '$user_id'";
    if (mysqli_query($koneksi, $delete_query)) {
        echo "<script>alert('User berhasil dihapus!'); window.location='admin-dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus user!');</script>";
    }
}

if (isset($_GET['delete_faskes'])) {
    $faskes_id = $_GET['delete_faskes'];
    $delete_query = "DELETE FROM faskes WHERE id = '$faskes_id'";
    if (mysqli_query($koneksi, $delete_query)) {
        echo "<script>alert('Fasilitas kesehatan berhasil dihapus!'); window.location='admin-dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus fasilitas kesehatan!');</script>";
    }
}

// Buat nampilin total user
$query_users = "SELECT COUNT(*) as total FROM users";
$result_users = mysqli_query($koneksi, $query_users);
$total_users = mysqli_fetch_assoc($result_users)['total'];

// Nampilin total faskes
$query_faskes = "SELECT COUNT(*) as total FROM faskes";
$result_faskes = mysqli_query($koneksi, $query_faskes);
$total_faskes = mysqli_fetch_assoc($result_faskes)['total'];

// Nampilin data users buat tabel brok
$query_list_users = "SELECT * FROM users ORDER BY created_at DESC";
$result_list_users = mysqli_query($koneksi, $query_list_users);

// Nampilin data faskes buat tabel
$query_list_faskes = "SELECT * FROM faskes ORDER BY nama ASC";
$result_list_faskes = mysqli_query($koneksi, $query_list_faskes);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - HealthCare+</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
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
            <h1><i class="fas fa-chart-line"></i> Admin Dashboard</h1>
            <p>Kelola pengguna dan fasilitas kesehatan HealthCare+</p>
        </div>

        <div class="quick-actions">
            <a href="add-faskes.php" class="quick-action-btn btn-add-faskes">
                <i class="fas fa-hospital"></i>
                Tambah Fasilitas Kesehatan
            </a>
            <a href="add-admin.php" class="quick-action-btn btn-add-admin">
                <i class="fas fa-user-plus"></i>
                Tambah Admin Baru
            </a>
        </div>

        <div class="dashboard-grid">
            <div class="admin-card">
                <div class="card-content">
                    <div class="card-title">Total User Terdaftar</div>
                    <div class="admin-count"><?php echo $total_users; ?></div>
                    <div class="card-subtitle">Pengguna aktif</div>
                </div>
                <i class="fas fa-users card-icon"></i>
            </div>

            <div class="admin-card">
                <div class="card-content">
                    <div class="card-title">Total Faskes Terdaftar</div>
                    <div class="admin-count"><?php echo $total_faskes; ?></div>
                    <div class="card-subtitle">Fasilitas kesehatan</div>
                </div>
                <i class="fas fa-hospital card-icon"></i>
            </div>
        </div>

        <div class="admin-table-container">
            <div class="table-header">
                <h2><i class="fas fa-users"></i> Manajemen User</h2>
            </div>
            
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result_list_users) > 0) {
                            while($user = mysqli_fetch_assoc($result_list_users)) {
                                $tanggal = date('d M Y', strtotime($user['created_at']));
                        ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo $tanggal; ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit-user.php?id=<?php echo $user['id']; ?>" class="btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="#" class="btn-delete" onclick="confirmDelete('user', <?php echo $user['id']; ?>)">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                        ?>
                        <tr>
                            <td colspan="5" class="empty-state">
                                <i class="fas fa-users"></i>
                                <p>Belum ada user terdaftar</p>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="admin-table-container">
            <div class="table-header">
                <h2><i class="fas fa-hospital"></i> Manajemen Fasilitas Kesehatan</h2>
                <a href="add-faskes.php" class="btn-table-add">
                    <i class="fas fa-plus"></i> Tambah Fasilitas
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Fasilitas</th>
                            <th>Tipe</th>
                            <th>Alamat</th>
                            <th>Kecamatan</th>
                            <th>Kontak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result_list_faskes) > 0) {
                            while($faskes = mysqli_fetch_assoc($result_list_faskes)) {
                                $badge_class = 'badge-rs';
                                if ($faskes['tipe'] == 'Klinik') $badge_class = 'badge-klinik';
                                elseif ($faskes['tipe'] == 'Puskesmas') $badge_class = 'badge-puskesmas';
                                elseif ($faskes['tipe'] == 'Apotek') $badge_class = 'badge-apotek';
                        ?>
                        <tr>
                            <td><?php echo $faskes['id']; ?></td>
                            <td><?php echo htmlspecialchars($faskes['nama']); ?></td>
                            <td><span class="badge <?php echo $badge_class; ?>"><?php echo $faskes['tipe']; ?></span></td>
                            <td><?php echo htmlspecialchars($faskes['alamat']); ?></td>
                            <td><?php echo htmlspecialchars($faskes['kecamatan']); ?></td>
                            <td><?php echo htmlspecialchars($faskes['kontak']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit-faskes.php?id=<?php echo $faskes['id']; ?>" class="btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="#" class="btn-delete" onclick="confirmDelete('faskes', <?php echo $faskes['id']; ?>)">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                        ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fas fa-hospital"></i>
                                <p>Belum ada fasilitas kesehatan terdaftar</p>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-container">
            <h3>HealthCare+ Admin</h3>
            <p>Platform informasi kesehatan terpercaya untuk Indonesia yang lebih sehat</p>
            <p class="copyright">&copy; 2025 HealthCare+. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const navMenu = document.getElementById('navMenu');
            navMenu.classList.toggle('active');
        }

        function confirmDelete(type, id) {
            const typeName = type === 'user' ? 'pengguna' : 'fasilitas kesehatan';
            if (confirm(`Yakin ingin menghapus ${typeName} ini?`)) {
                window.location.href = `admin-dashboard.php?delete_${type}=${id}`;
            }
        }

        document.addEventListener('click', function(event) {
            const navMenu = document.getElementById('navMenu');
            const navToggle = document.querySelector('.nav-toggle');
            
            if (navMenu && navToggle && !navMenu.contains(event.target) && !navToggle.contains(event.target)) {
                navMenu.classList.remove('active');
            }
        });
    </script>
</body>
</html>