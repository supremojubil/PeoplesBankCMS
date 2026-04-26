<?php
require_once 'db_function/db.php';

// Fetch published topics
$stmt = $pdo->query("SELECT * FROM topics WHERE status = 'published' ORDER BY created_at DESC");
$topics = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topics - People's Bank</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .topic-card {
            transition: transform 0.2s;
            margin-bottom: 20px;
        }

        .topic-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .content-item {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 5px;
        }

        .content-image {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .footer {
            background: #0a2540;
            color: #fff;
            padding: 40px 0;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <?php include 'includes/navbar.php'; ?>

    <!-- HERO -->
    <section class="bg-primary text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4">Our Topics</h1>
                    <p class="lead">Explore our latest information and resources</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TOPICS SECTION -->
    <section class="py-5">
        <div class="container">
            <?php if (empty($topics)): ?>
                <div class="text-center">
                    <h3>No topics available at the moment.</h3>
                    <p>Please check back later.</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($topics as $topic): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card topic-card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($topic['title']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars(substr($topic['description'], 0, 150)); ?>...</p>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#topicModal<?php echo $topic['id']; ?>">
                                        View Details
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Topic Modal -->
                        <div class="modal fade" id="topicModal<?php echo $topic['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><?php echo htmlspecialchars($topic['title']); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php if (!empty($topic['description'])): ?>
                                            <p class="mb-4"><?php echo nl2br(htmlspecialchars($topic['description'])); ?></p>
                                        <?php endif; ?>

                                        <?php
                                        // Fetch content for this topic
                                        $stmt = $pdo->prepare("SELECT * FROM topic_content WHERE topic_id = ? AND status = 'active' ORDER BY sort_order, created_at");
                                        $stmt->execute([$topic['id']]);
                                        $contents = $stmt->fetchAll();
                                        ?>

                                        <?php if (!empty($contents)): ?>
                                            <h6>Content:</h6>
                                            <?php foreach ($contents as $content): ?>
                                                <div class="content-item">
                                                    <h6><?php echo htmlspecialchars($content['title']); ?></h6>

                                                    <?php if ($content['type'] === 'text'): ?>
                                                        <div><?php echo nl2br(htmlspecialchars($content['content'])); ?></div>
                                                    <?php elseif ($content['type'] === 'image'): ?>
                                                        <img src="<?php echo htmlspecialchars($content['content']); ?>" class="content-image" alt="<?php echo htmlspecialchars($content['title']); ?>">
                                                    <?php elseif ($content['type'] === 'file'): ?>
                                                        <p><i class="bi bi-file-earmark"></i> <?php echo htmlspecialchars($content['file_name']); ?></p>
                                                        <a href="<?php echo htmlspecialchars($content['content']); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-download"></i> Download
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if (!empty($content['description'])): ?>
                                                        <p class="text-muted mt-2"><em><?php echo htmlspecialchars($content['description']); ?></em></p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p class="text-muted">No content available for this topic.</p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- FOOTER -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>