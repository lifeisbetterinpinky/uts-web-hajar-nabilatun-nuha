<?php
include 'config/koneksi.php';
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['status'] = "login";
        header("location:index.php");
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login to your account</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-body">
    <div class="login-card">
        <h1>login to your account</h1>
        
        <?php if(isset($error)) { echo "<p style='color: #c0392b; font-size: 0.8rem; margin-bottom: 10px;'>$error</p>"; } ?>
        
        <form action="" method="POST">
            <div class="form-group">
                <label>Username / Email Address</label>
                <input type="text" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="login" class="btn-masuk">Login</button>
        </form>
        
        <a href="#" class="small-link">lupa password?</a>
        <div class="login-footer">
            UTS Praktikum Pemrograman Web 1 <br>
            Don't have an account? <a href="register.php">Register</a>
        </div>
    </div>
</body>
</html>