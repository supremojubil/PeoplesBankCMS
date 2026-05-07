<?php
include_once 'db_function/db.php';

// Fetch published announcements
$stmt = $pdo->prepare("SELECT * FROM announcements WHERE status = 'published' ORDER BY posted_at DESC");
$stmt->execute();
$announcements = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>What’s New - Ozamiz City People's MPC</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/logo.png">

    <style>
        :root {
            --primary-blue: #002366;
            --primary-light: #0d6efd;
            --accent-gold: #ffc107;
            --bg-light: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--bg-light);
        }

        /* PAGE HEADER */
        .page-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-light));
            color: white;
            padding: 80px 0;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
        /* Navbar styles */
        .navbar {
            transition: all 0.4s ease-in-out;
            padding: 15px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar.scrolled {
            background-color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 5px 0;
        }

        /* NAV LINK BASE */
        .nav-link {
            position: relative;
            font-weight: 500;
            transition: color 0.3s ease;
            color: #333;
        }

        /* HOVER ANIMATION */
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #0d6efd;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link:hover {
            color: #0d6efd !important;
        }

        /* ACTIVE LINK (IMPORTANT) */
        .nav-link.active {
            color: #0d6efd !important;
        }

        .nav-link.active::after {
            width: 100%;
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

        /* NEWS CARD */
        .news-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: 0.3s;
            height: 100%;
        }

        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .news-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .news-body {
            padding: 20px;
        }

        .news-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .news-date {
            font-size: 0.85rem;
            color: #777;
            margin-bottom: 10px;
        }

        .news-text {
            font-size: 0.95rem;
            color: #555;
            height: 60px;
            overflow: hidden;
        }

        .btn-read {
            margin-top: 10px;
            background: var(--primary-light);
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 6px;
            font-size: 0.85rem;
        }

        .btn-read:hover {
            background: var(--primary-blue);
        }

        /* EMPTY STATE */
        .empty {
            text-align: center;
            padding: 60px;
            color: #777;
        }
    </style>
</head>

<body>

<?php include_once 'includes/navbar.php'; ?>

<!-- HEADER -->
<div class="page-header">
    <h1>What’s New</h1>
    <p>Latest announcements, updates, and news from the cooperative</p>
</div>

<!-- CONTENT -->
<div class="container my-5">

    <h2 class="section-title">Latest Announcements</h2>

    <div class="row g-4">

        <?php if (count($announcements) > 0): ?>
            <?php foreach ($announcements as $row): ?>
                <div class="col-md-6 col-lg-4">

                    <div class="news-card">

                        <!-- IMAGE -->
                        <?php if (!empty($row['image'])): ?>
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" class="news-img" alt="">
                        <?php else: ?>
                            <img src="assets/img/default-news.jpg" class="news-img" alt="">
                        <?php endif; ?>

                        <!-- BODY -->
                        <div class="news-body">

                            <div class="news-date">
                                <i class="bi bi-calendar"></i>
                                <?php echo date('F d, Y', strtotime($row['posted_at'])); ?>
                            </div>

                            <div class="news-title">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </div>

                            <p class="news-text">
                                <?php echo nl2br(htmlspecialchars(substr($row['content'], 0, 120))); ?>...
                            </p>

                            <!-- MODAL BUTTON -->
                            <button class="btn-read" data-bs-toggle="modal" data-bs-target="#newsModal<?php echo $row['id']; ?>">
                                Read More
                            </button>

                        </div>
                    </div>

                </div>

                <!-- MODAL -->
                <div class="modal fade" id="newsModal<?php echo $row['id']; ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <?php if (!empty($row['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($row['image']); ?>" class="img-fluid mb-3 rounded">
                                <?php endif; ?>

                                <p style="white-space: pre-line;">
                                    <?php echo htmlspecialchars($row['content']); ?>
                                </p>

                                <small class="text-muted">
                                    Posted on <?php echo date('F d, Y h:i A', strtotime($row['posted_at'])); ?>
                                </small>

                            </div>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>

            <div class="col-12 empty">
                <i class="bi bi-megaphone" style="font-size: 50px;"></i>
                <h5 class="mt-3">No announcements yet</h5>
                <p>Check back later for updates.</p>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>