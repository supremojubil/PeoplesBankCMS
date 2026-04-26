<?php
include_once 'db_function/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>People's Bank Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Header -->
    <header class="header">
        <h1>People's Bank Information System</h1>
    </header>

    <div class="container">
        <!-- Sidebar -->
        <?php include_once 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main">
            <div class="welcome-box">
                <h2>Welcome Admin</h2>
                <p>Dashboard Overview</p>
            </div>

            <!-- Summary Cards -->
            <section class="cards">
                <div class="card">
                    <h3>Total Expenses</h3>
                    <p>₱1000</p>
                </div>
                <div class="card">
                    <h3>This Month</h3>
                    <p>₱1000</p>
                </div>
                <div class="card">
                    <h3>Remaining Balance</h3>
                      <p>₱1000</p>
                </div>
            </section>
        </main>
    </div>

</body>
</html>
