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
    <div class="login-container">
    <h2>People's Bank</h2>

    <!-- SUCCESS MESSAGE -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="success">
            <?= $_SESSION['success']; ?>
        </div>
    <?php unset($_SESSION['success']); endif; ?>

    <!--  ERROR MESSAGE -->
    <?php if (isset($_GET['error'])): ?>
        <div class="error">Invalid username or password</div>
    <?php endif; ?>

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
    </div>
</div>

<script>
setTimeout(() => {
    const msg = document.querySelector('.success');
    if (msg) msg.style.display = '0';
}, 3000);
</script>

</body>
</html>