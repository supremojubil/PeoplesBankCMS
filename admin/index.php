<?php
include_once '../db_function/db.php';
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
        <?php include_once '../includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main">
            <div class="welcome-box">
                <h2>Welcome Admin</h2>
                <p>Dashboard Overview</p>
            </div>

            <!-- Summary Cards -->
            <section class="cards">
                <div class="card">
                    <h3>Total Topics</h3>
                    <p><?php
                        $stmt = $pdo->query("SELECT COUNT(*) as count FROM topics");
                        echo $stmt->fetch()['count'];
                        ?></p>
                </div>
                <div class="card">
                    <h3>Published Topics</h3>
                    <p><?php
                        $stmt = $pdo->query("SELECT COUNT(*) as count FROM topics WHERE status = 'published'");
                        echo $stmt->fetch()['count'];
                        ?></p>
                </div>
                <div class="card">
                    <h3>Total Content</h3>
                    <p><?php
                        $stmt = $pdo->query("SELECT COUNT(*) as count FROM topic_content");
                        echo $stmt->fetch()['count'];
                        ?></p>
                </div>
            </section>
        </main>
    </div>

</body>

</html>