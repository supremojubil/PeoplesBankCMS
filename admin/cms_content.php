<?php
session_start();
require_once '../db_function/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$topic_id = $_GET['topic_id'] ?? 0;

// Fetch topic details
$stmt = $pdo->prepare("SELECT * FROM topics WHERE id = ?");
$stmt->execute([$topic_id]);
$topic = $stmt->fetch();

if (!$topic) {
    header("Location: cms_topics.php");
    exit();
}

// Handle content addition
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $content = '';

    if ($type === 'text') {
        $content = $_POST['text_content'];
    } elseif ($type === 'image' || $type === 'file') {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_name = basename($_FILES['file']['name']);
            $file_path = $upload_dir . time() . '_' . $file_name;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
                $content = $file_path;
            }
        }
    }

    if (!empty($title) && !empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO topic_content (topic_id, type, title, content, file_name, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$topic_id, $type, $title, $content, $_FILES['file']['name'] ?? '', $description]);

        header("Location: cms_content.php?topic_id=$topic_id&success=added");
        exit();
    }
}

// Handle content deletion
if (isset($_GET['delete_content']) && is_numeric($_GET['delete_content'])) {
    $stmt = $pdo->prepare("SELECT * FROM topic_content WHERE id = ?");
    $stmt->execute([$_GET['delete_content']]);
    $content_item = $stmt->fetch();

    if ($content_item && ($content_item['type'] === 'image' || $content_item['type'] === 'file')) {
        if (file_exists($content_item['content'])) {
            unlink($content_item['content']);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM topic_content WHERE id = ?");
    $stmt->execute([$_GET['delete_content']]);

    header("Location: cms_content.php?topic_id=$topic_id&success=deleted");
    exit();
}

// Fetch all content for this topic
$stmt = $pdo->prepare("SELECT * FROM topic_content WHERE topic_id = ? ORDER BY sort_order, created_at");
$stmt->execute([$topic_id]);
$content_items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Content - <?php echo htmlspecialchars($topic['title']); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .cms-container {
            margin-left: 250px;
            padding: 20px;
        }

        .content-item {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .content-preview {
            max-width: 200px;
            max-height: 150px;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <?php include_once '../includes/sidebar.php'; ?>

    <div class="cms-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Manage Content: <?php echo htmlspecialchars($topic['title']); ?></h1>
            <a href="cms_topics.php" class="btn btn-secondary">← Back to Topics</a>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php if ($_GET['success'] == 'added'): ?>
                    Content added successfully!
                <?php elseif ($_GET['success'] == 'deleted'): ?>
                    Content deleted successfully!
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Add Content Button -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addContentModal">
            + Add Content
        </button>

        <!-- Content List -->
        <div class="row">
            <?php foreach ($content_items as $item): ?>
                <div class="col-md-6">
                    <div class="content-item">
                        <h5><?php echo htmlspecialchars($item['title']); ?></h5>
                        <p><strong>Type:</strong> <?php echo ucfirst($item['type']); ?></p>

                        <?php if ($item['type'] === 'text'): ?>
                            <div><?php echo nl2br(htmlspecialchars(substr($item['content'], 0, 200))); ?>...</div>
                        <?php elseif ($item['type'] === 'image'): ?>
                            <img src="<?php echo htmlspecialchars($item['content']); ?>" class="content-preview img-thumbnail" alt="Content Image">
                        <?php elseif ($item['type'] === 'file'): ?>
                            <p><i class="bi bi-file-earmark"></i> <?php echo htmlspecialchars($item['file_name']); ?></p>
                            <a href="<?php echo htmlspecialchars($item['content']); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Download</a>
                        <?php endif; ?>

                        <?php if (!empty($item['description'])): ?>
                            <p><em><?php echo htmlspecialchars($item['description']); ?></em></p>
                        <?php endif; ?>

                        <div class="btn-group mt-2">
                            <a href="cms_content.php?topic_id=<?php echo $topic_id; ?>&delete_content=<?php echo $item['id']; ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure you want to delete this content?')">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add Content Modal -->
    <div class="modal fade" id="addContentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Content to Topic</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Content Type</label>
                            <select class="form-control" name="type" id="contentType" required>
                                <option value="">Select Type</option>
                                <option value="text">Text</option>
                                <option value="image">Image</option>
                                <option value="file">File</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>

                        <div class="mb-3" id="textContentDiv" style="display: none;">
                            <label class="form-label">Text Content</label>
                            <textarea class="form-control" name="text_content" rows="5"></textarea>
                        </div>

                        <div class="mb-3" id="fileContentDiv" style="display: none;">
                            <label class="form-label">File</label>
                            <input type="file" class="form-control" name="file">
                            <small class="text-muted">For images: JPG, PNG, GIF. For files: PDF, DOC, etc.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description (Optional)</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Content</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('contentType').addEventListener('change', function() {
            const type = this.value;
            document.getElementById('textContentDiv').style.display = type === 'text' ? 'block' : 'none';
            document.getElementById('fileContentDiv').style.display = (type === 'image' || type === 'file') ? 'block' : 'none';
        });
    </script>
</body>

</html>