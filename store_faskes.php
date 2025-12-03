<?php
session_start();
include 'koneksi.php';

// Cek Admin
if (!isset($_SESSION['login_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $tipe = $_POST['tipe'];
    $kecamatan = mysqli_real_escape_string($koneksi, $_POST['kecamatan']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $kontak = mysqli_real_escape_string($koneksi, $_POST['kontak']);

    $query = "INSERT INTO faskes (nama, tipe, alamat, kecamatan, kontak) 
              VALUES ('$nama', '$tipe', '$alamat', '$kecamatan', '$kontak')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Faskes berhasil ditambahkan!'); window.location='admin-dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!'); window.history.back();</script>";
    }
}
?>