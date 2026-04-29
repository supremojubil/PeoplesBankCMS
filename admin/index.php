<?php
include_once '../db_function/db.php';

$totalTopics = $pdo->query("SELECT COUNT(*) as count FROM topics")->fetch()['count'];
$publishedTopics = $pdo->query("SELECT COUNT(*) as count FROM topics WHERE status = 'published'")->fetch()['count'];
$draftTopics = $pdo->query("SELECT COUNT(*) as count FROM topics WHERE status = 'draft'")->fetch()['count'];
$archivedTopics = $pdo->query("SELECT COUNT(*) as count FROM topics WHERE status = 'archived'")->fetch()['count'];
$totalContent = $pdo->query("SELECT COUNT(*) as count FROM topic_content")->fetch()['count'];
$recentTopics = $pdo->query("SELECT title, status, updated_at FROM topics ORDER BY updated_at DESC LIMIT 4")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>People's Bank Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <!-- Header -->
    <header class="header">
        <div class="header-row">
            <div>
                <p class="eyebrow">Admin dashboard</p>
                <h1>People's Bank Information System</h1>
            </div>
            <div class="header-meta">Welcome back, Admin</div>
        </div>
    </header>

    <div class="container">
        <!-- Sidebar -->
        <?php include_once '../includes/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main">
            <div class="dashboard-top">
                <div class="welcome-box">
                    <h2>Dashboard Overview</h2>
                    <p>Manage content, review activity, and keep your site aligned with the People's Bank brand.</p>
                </div>
                <div class="action-panel">
                    <a class="btn btn-primary" href="cms_topics.php">Manage Topics</a>
                    <a class="btn btn-secondary" href="settings.php">Platform Settings</a>
                </div>
            </div>

            <!-- Summary Cards -->
            <section class="stats-grid">
                <div class="card">
                    <h3>Total Topics</h3>
                    <p><?= $totalTopics ?></p>
                </div>
                <div class="card">
                    <h3>Published Topics</h3>
                    <p><?= $publishedTopics ?></p>
                </div>
                <div class="card">
                    <h3>Draft Topics</h3>
                    <p><?= $draftTopics ?></p>
                </div>
                <div class="card">
                    <h3>Total Content</h3>
                    <p><?= $totalContent ?></p>
                </div>
            </section>

            <section class="dashboard-panels">
                <div class="panel">
                    <div class="panel-header">
                        <h3>Latest Topics</h3>
                        <span class="panel-meta"><?= count($recentTopics) ?> items</span>
                    </div>
                    <ul class="panel-list">
                        <?php if ($recentTopics): ?>
                            <?php foreach ($recentTopics as $topic): ?>
                                <li>
                                    <span><?= htmlspecialchars($topic['title']) ?></span>
                                    <span class="status <?= htmlspecialchars($topic['status']) ?>"><?= ucfirst($topic['status']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>No recent topics available yet.</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="panel">
                    <div class="panel-header">
                        <h3>Quick Actions</h3>
                    </div>
                    <ul class="panel-list quick-links">
                        <li><a href="cms_topics.php">View all CMS topics</a></li>
                        <li><a href="cms_topics.php#add-topic">Add new topic</a></li>
                        <li><a href="reports.php">Review reports</a></li>
                        <li><a href="settings.php">Update platform settings</a></li>
                    </ul>
                </div>
            </section>
        </main>
    </div>

</body>

</html>