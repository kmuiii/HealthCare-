<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['password_confirm'];

    // UPDATE PASSWORD
    // klo kolom password diisi, berarti mau ganti password
    if (!empty($password)) {
        if ($password !== $confirm) {
            echo "<script>alert('Konfirmasi password baru tidak cocok!'); window.history.back();</script>";
            exit;
        }
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE users SET username='$username', email='$email', password='$hashed_pass' WHERE id='$id'";
    } else {
        // Jika kosong, berarti cuma ganti nama/email, password lama tetap
        $query = "UPDATE users SET username='$username', email='$email' WHERE id='$id'";
    }

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data User berhasil diperbarui!'); window.location='admin-dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal update user!'); window.history.back();</script>";
    }
}
?>