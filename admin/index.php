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
<title>Admin Dashboard</title>

<link rel="stylesheet" href="../assets/css/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
:root{
    --primary:#002366;
    --success:#28a745;
    --warning:#ffc107;
    --danger:#dc3545;
    --muted:#6c757d;
}

body{
    background:#f5f7fa;
}

/* Layout */
.sidebar{
    position:fixed;
    width:260px;
    height:100vh;
}

.cms-container{
    margin-left:260px;
    padding:25px;
}

/* Header */
.page-title{
    font-size:28px;
    font-weight:800;
    color:var(--primary);
}
.page-subtitle{
    font-size:13px;
    color:var(--muted);
}

/* Top panel */
.top-bar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 8px rgba(0,0,0,.08);
    margin-bottom:20px;
}

/* Stats */
.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:15px;
    margin-bottom:25px;
}

.stat-card{
    background:#fff;
    padding:18px;
    border-radius:10px;
    box-shadow:0 2px 6px rgba(0,0,0,.08);
    border-left:4px solid var(--primary);
    transition:.2s;
}

.stat-card:hover{
    transform:translateY(-3px);
}

.stat-card h4{
    font-size:13px;
    color:var(--muted);
    margin-bottom:5px;
}

.stat-card .value{
    font-size:24px;
    font-weight:800;
}

.stat-card.success{ border-color:var(--success); }
.stat-card.warning{ border-color:var(--warning); }
.stat-card.danger{ border-color:var(--danger); }

/* Panels */
.dashboard-grid{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:20px;
}

.panel{
    background:#fff;
    border-radius:10px;
    box-shadow:0 2px 6px rgba(0,0,0,.08);
    overflow:hidden;
}

.panel-header{
    padding:15px 18px;
    border-bottom:1px solid #eee;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.panel-header h3{
    font-size:16px;
    margin:0;
    color:var(--primary);
}

.panel-body{
    padding:15px 18px;
}

/* List */
.list-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:10px 0;
    border-bottom:1px solid #f1f1f1;
}

.list-item:last-child{
    border-bottom:none;
}

.status{
    font-size:12px;
    padding:4px 10px;
    border-radius:20px;
    color:#fff;
}

.status.published{ background:var(--success); }
.status.draft{ background:var(--warning); color:#000; }
.status.archived{ background:var(--muted); }

/* Buttons */
.btn-primary{
    background:var(--primary);
    border:none;
}

.quick-btn{
    display:block;
    padding:10px;
    margin-bottom:8px;
    border-radius:6px;
    background:#f8f9fa;
    text-decoration:none;
    color:#333;
    transition:.2s;
}

.quick-btn:hover{
    background:#e9ecef;
}
</style>
</head>

<body>

<?php include_once '../includes/sidebar.php'; ?>

<div class="cms-container">

    <!-- HEADER -->
    <div class="top-bar">
        <div>
            <div class="page-title">Dashboard</div>
            <div class="page-subtitle">People's Bank Information System Overview</div>
        </div>

        <div>
            <span class="text-muted">Welcome back, Admin</span>
        </div>
    </div>

    <!-- STATS -->
    <div class="stats-grid">

        <div class="stat-card">
            <h4>Total Reports</h4>
            <div class="value"><?= $totalTopics ?></div>
        </div>

        <div class="stat-card success">
            <h4>Published</h4>
            <div class="value"><?= $publishedTopics ?></div>
        </div>

        <div class="stat-card warning">
            <h4>Draft</h4>
            <div class="value"><?= $draftTopics ?></div>
        </div>

        <div class="stat-card danger">
            <h4>Archived</h4>
            <div class="value"><?= $archivedTopics ?></div>
        </div>

        <div class="stat-card">
            <h4>Total Content</h4>
            <div class="value"><?= $totalContent ?></div>
        </div>

    </div>

    <!-- MAIN GRID -->
    <div class="dashboard-grid">

        <!-- RECENT TOPICS -->
        <div class="panel">
            <div class="panel-header">
                <h3>Latest Reports</h3>
                <small class="text-muted"><?= count($recentTopics) ?> items</small>
            </div>

            <div class="panel-body">
                <?php if ($recentTopics): ?>
                    <?php foreach ($recentTopics as $topic): ?>
                        <div class="list-item">
                            <div>
                                <strong><?= htmlspecialchars($topic['title']) ?></strong>
                                <div class="text-muted" style="font-size:12px;">
                                    <?= date('M d, Y', strtotime($topic['updated_at'])) ?>
                                </div>
                            </div>

                            <span class="status <?= $topic['status'] ?>">
                                <?= ucfirst($topic['status']) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No recent report available.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="panel">
            <div class="panel-header">
                <h3>Quick Actions</h3>
            </div>

            <div class="panel-body">
                <a href="cms_topics.php" class="quick-btn">
                    <i class="fa fa-chart-line me-2"></i> View Report
                </a>

                <a href="cms_topics.php#add-topic" class="quick-btn">
                    <i class="fa fa-plus me-2"></i> Add New Report
                </a>

                <a href="settings.php" class="quick-btn">
                    <i class="fa fa-gear me-2"></i> System Settings
                </a>
            </div>
        </div>

    </div>

</div>

</body>
</html>