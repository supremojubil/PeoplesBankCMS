<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | People's Bank</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="overlay"></div>
<div class="login-wrapper">
    <div class="login-card">
        <div class="login-brand">
            <img src="assets/img/peoplesbanklogo.jpg" alt="People's Bank Logo">
            <div>
                <h2>People's Bank</h2>
                <p class="login-subtitle">Secure access to the CMS dashboard</p>
            </div>
        </div>

        <!-- SUCCESS MESSAGE -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success">
                <?= $_SESSION['success']; ?>
            </div>
        <?php unset($_SESSION['success']); endif; ?>

        <!-- ERROR MESSAGE -->
        <?php if (isset($_GET['error'])): ?>
            <div class="error">Invalid username or password</div>
        <?php endif; ?>

        <p class="login-helper">Enter your username and password to continue.</p>

        <form action="db_function/login_process.php" method="POST">
            <div class="input-box">
                <input type="text" name="username" required>
                <label>Username</label>
            </div>

            <div class="input-box">
                <input type="password" name="password" required>
                <label>Password</label>
            </div>

            <button type="submit">Login</button>
        </form>
        <p class="login-footer">Need help? Contact your system administrator.</p>
    </div>
</div>

<script>
setTimeout(() => {
    const msg = document.querySelector('.success');
    if (msg) msg.style.display = 'none';
}, 3000);
</script>

</body>
</html>