<?php

include 'koneksi.php';

$error_msg = "";
$success_msg = "";

if(isset($_POST['register'])){
    $fullname = mysqli_real_escape_string($koneksi, $_POST['fullname']);
    $email = mysqli_real_escape_string($koneksi,$_POST['email']);
    $password= $_POST['password'];
    $confirm_password = $_POST['confirm_password'];



    if($password !== $confirm_password){
        $error_msg = "Password dan Konfirmasi Password tidak sesuai!";
    } else {
        $cek_email = mysqli_query($koneksi, "SELECT email FROM users WHERE email = '$email'");
        if(mysqli_num_rows($cek_email)>0){
            $error_msg = "Email sudah terdaftar!";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, email, password) VALUES ('$fullname', '$email', '$hashed_password')";

            if(mysqli_query($koneksi, $query)){
                echo "<script>alert('Registrasi berhasil! Silahkan Login.'); window.location='login.php';</script>";
            } else {
                $error_msg = "Registrasi Gagal: ".mysqli_error($koneksi);
            }
        }
    }
}

?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - HealthCare+</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="auth-container fade-in">
        <div class="auth-card">
            <h2>Buat Akun Baru</h2>
            <p>Daftar untuk mengakses HealthCare+</p>

            <?php if($error_msg): ?>
                <div class="error-msg show" style="display:block;"><?php echo $error_msg; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="register.php">
                
                <div class="form-group">
                    <label for="fullname">Nama Lengkap</label>
                    <input 
                        type="text" 
                        id="fullname" 
                        name="fullname" 
                        placeholder="Nama lengkap Anda" 
                        required 
                    />
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Alamat Email" 
                        required 
                    />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Minimal 6 karakter" 
                        required 
                    />
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        placeholder="Ketik ulang password" 
                        required 
                    />
                </div>

                <button type="submit" class="btn-primary btn-login-reg" name="register">Daftar</button>
            </form>

            <div class="auth-link">
                Sudah punya akun? <a href="login.php">Login sekarang</a>
            </div>
        </div>
    </div>
</body>
</html>