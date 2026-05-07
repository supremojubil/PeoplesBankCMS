<?php
require_once 'db_function/db.php';

// Fetch topics
$stmt = $pdo->query("SELECT * FROM topics WHERE status = 'published' ORDER BY created_at DESC");
$topics = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - People's Bank</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/img/logo.png">

    <style>
        :root {
            --primary-blue: #002366;
            --primary-light: #0d6efd;
            --accent-gold: #ffc107;
            --bg-light: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: var(--bg-light);
        }

        /* KEEP YOUR NAVBAR STYLE (UNCHANGED) */
        .navbar {
            transition: all 0.4s ease-in-out;
            padding: 15px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar.scrolled {
            background-color: #fff !important;
            padding: 5px 0;
        }

        .nav-link {
            position: relative;
            font-weight: 500;
            color: #333;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background: var(--primary-light);
            transition: width 0.3s;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link:hover {
            color: var(--primary-light) !important;
        }

        .nav-link.active {
            color: var(--primary-light) !important;
        }

        /* HEADER */
        .page-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-light));
            color: white;
            padding: 90px 0;
            text-align: center;
        }

        .page-header h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .page-header p {
            font-size: 1.3rem;
            opacity: 0.95;
        }

        /* SECTION TITLE */
        .section-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 40px;
            position: relative;
        }

        .section-title::after {
            content: "";
            width: 80px;
            height: 4px;
            background: var(--accent-gold);
            position: absolute;
            left: 0;
            bottom: -10px;
            border-radius: 5px;
        }

        /* TOPIC CARD */
        .topic-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            height: 100%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: 0.3s;
            border-left: 5px solid var(--primary-light);
        }

        .topic-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            border-left-color: var(--accent-gold);
        }

        .topic-date {
            font-size: 0.85rem;
            color: #777;
        }

        .topic-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .topic-desc {
            font-size: 0.95rem;
            color: #555;
        }

        .btn-view {
            background: var(--primary-light);
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 6px;
            font-size: 0.85rem;
        }

        .btn-view:hover {
            background: var(--primary-blue);
        }

        /* MODAL CONTENT */
        .content-box {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            background: #fff;
        }

        .content-image {
            width: 100%;
            border-radius: 8px;
        }
    </style>
</head>

<body>

<?php include 'includes/navbar.php'; ?>

<!-- HEADER -->
<div class="page-header">
    <div class="container">
        <h1>Reports</h1>
        <p>News, guides, and important information</p>
    </div>
</div>

<!-- CONTENT -->
<div class="container py-5">

    <h2 class="section-title">Latest Reports</h2>

    <div class="row g-4">

        <?php if (empty($topics)): ?>
            <div class="col-12 text-center text-muted">
                No reports available.
            </div>
        <?php endif; ?>

        <?php foreach ($topics as $topic): ?>
            <div class="col-md-6 col-lg-4">

                <div class="topic-card">

                    <div class="topic-date mb-2">
                        <i class="bi bi-calendar"></i>
                        <?= date('F d, Y', strtotime($topic['created_at'])) ?>
                    </div>

                    <div class="topic-title mb-2">
                        <?= htmlspecialchars($topic['title']) ?>
                    </div>

                    <p class="topic-desc">
                        <?= substr(strip_tags($topic['description']), 0, 120) ?>...
                    </p>

                    <button class="btn-view mt-2"
                        data-bs-toggle="modal"
                        data-bs-target="#topicModal<?= $topic['id'] ?>">
                        View Details
                    </button>

                </div>

            </div>

            <!-- MODAL -->
            <div class="modal fade" id="topicModal<?= $topic['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">
                                <?= htmlspecialchars($topic['title']) ?>
                            </h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <p><?= nl2br(htmlspecialchars($topic['description'])) ?></p>

                            <?php
                            $stmtC = $pdo->prepare("
                                SELECT * FROM topic_content 
                                WHERE topic_id = ? AND status = 'active' 
                                ORDER BY sort_order, created_at
                            ");
                            $stmtC->execute([$topic['id']]);
                            $contents = $stmtC->fetchAll();
                            ?>

                            <?php foreach ($contents as $c): ?>
                                <div class="content-box">

                                    <h6><?= htmlspecialchars($c['title']) ?></h6>

                                    <?php if ($c['type'] == 'text'): ?>
                                        <p><?= nl2br(htmlspecialchars($c['content'])) ?></p>

                                    <?php elseif ($c['type'] == 'image'): ?>
                                        <img src="<?= htmlspecialchars($c['content']) ?>" class="content-image">

                                    <?php elseif ($c['type'] == 'file'): ?>
                                        <a href="<?= $c['content'] ?>" class="btn btn-sm btn-outline-primary">
                                            Download File
                                        </a>
                                    <?php endif; ?>

                                </div>
                            <?php endforeach; ?>

                        </div>

                    </div>
                </div>
            </div>

        <?php endforeach; ?>

    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>