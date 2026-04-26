<?php
session_start();
require_once '../db_function/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Handle topic creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_topic'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    if (!empty($title)) {
        $slug = strtolower(str_replace(' ', '-', preg_replace('/[^A-Za-z0-9\-]/', '', $title)));

        $stmt = $pdo->prepare("INSERT INTO topics (title, description, slug, status, created_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $slug, $status, $_SESSION['user_id']]);

        header("Location: cms_topics.php?success=created");
        exit();
    }
}

// Handle topic deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM topics WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: cms_topics.php?success=deleted");
    exit();
}

// Fetch all topics
$stmt = $pdo->query("SELECT * FROM topics ORDER BY created_at DESC");
$topics = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CMS Topics - People's Bank</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .cms-container {
            margin-left: 250px;
            padding: 20px;
        }

        .topic-card {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .status-published {
            color: green;
        }

        .status-draft {
            color: orange;
        }

        .status-archived {
            color: red;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <?php include_once '../includes/sidebar.php'; ?>

    <div class="cms-container">
        <h1>CMS Topics Management</h1>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php if ($_GET['success'] == 'created'): ?>
                    Topic created successfully!
                <?php elseif ($_GET['success'] == 'deleted'): ?>
                    Topic deleted successfully!
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Create New Topic Button -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createTopicModal">
            + Create New Topic
        </button>

        <!-- Topics List -->
        <div class="row">
            <?php foreach ($topics as $topic): ?>
                <div class="col-md-6">
                    <div class="topic-card">
                        <h4><?php echo htmlspecialchars($topic['title']); ?></h4>
                        <p><?php echo htmlspecialchars(substr($topic['description'], 0, 100)); ?>...</p>
                        <p><small class="text-muted">Created: <?php echo date('M d, Y', strtotime($topic['created_at'])); ?></small></p>
                        <p><span class="status-<?php echo $topic['status']; ?>"><?php echo ucfirst($topic['status']); ?></span></p>
                        <div class="btn-group">
                            <a href="cms_content.php?topic_id=<?php echo $topic['id']; ?>" class="btn btn-sm btn-info">Manage Content</a>
                            <a href="cms_topics.php?delete=<?php echo $topic['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Create Topic Modal -->
    <div class="modal fade" id="createTopicModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Topic</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Topic Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="create_topic" class="btn btn-primary">Create Topic</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>