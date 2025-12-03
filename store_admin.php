<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['password_confirm'];
    $role = 'admin'; // Paksa role jadi admin

    if ($password !== $confirm) {
        echo "<script>alert('Konfirmasi password tidak cocok!'); window.history.back();</script>";
        exit;
    }

    // Cek email kembar
    $cek = mysqli_query($koneksi, "SELECT email FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Email sudah terdaftar!'); window.history.back();</script>";
        exit;
    }

    $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

    // Pastikan tabel users sudah ada kolom 'role' ya!
    $query = "INSERT INTO users (username, email, password, role) 
              VALUES ('$username', '$email', '$hashed_pass', '$role')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Admin baru berhasil ditambahkan!'); window.location='admin-dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah admin!'); window.history.back();</script>";
    }
}
?>