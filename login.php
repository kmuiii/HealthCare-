<?php
    session_start();
    include 'koneksi.php';

    if(isset($_SESSION['login_user'])){
        if ($_SESSION['role'] === 'admin') {
            header('Location: admin-dashboard.php');
            
        } else {
            header('Location: index.php');
        }
       
        exit();
    }
  
    $error_msg = "";

    if(isset($_POST['login'])){
        $email = mysqli_real_escape_string($koneksi, $_POST['email']);
        $password = $_POST['password'];

        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($koneksi, $query);
        
        if(mysqli_num_rows($result) === 1){
            $row = mysqli_fetch_assoc($result);

            if(password_verify($password, $row['password'])){

                $_SESSION['login_user'] = true;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['fullname'] = $row['username'];

                if ($email === 'admin@gmail.com') {
                    $_SESSION['role'] = 'admin';
                    header("location: admin-dashboard.php");
                } else {
                    $_SESSION['role'] = 'user';
                    header("location: index.php");
                }
                exit;
            } else{
                $error_msg = "Password Salah!";
            }

        } else {
            $error_msg = "Email tidak ditemukan!";
        }
    }

?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HealthCare+</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="auth-container fade-in">
        <div class="auth-card">
            <h2>Selamat Datang</h2>
            <p>Masuk ke akun HealthCare+ Anda</p>
            

            <?php if($error_msg): ?>
                <div class="error-msg show" style="display:block;"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            
            <form method="POST" action="">
                
                
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
                        placeholder="Masukkan password" 
                        required 
                    />
                </div>

                <button type="submit" class="btn-primary btn-login-reg" name="login">Login</button>
            </form>

            <div class="auth-link">
                Belum punya akun? <a href="register.php">Daftar disini</a>
            </div>
        </div>
    </div>
</body>
</html>