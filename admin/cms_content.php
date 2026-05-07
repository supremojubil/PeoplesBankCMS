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
        $stmt = $pdo->prepare("INSERT INTO topic_content (topic_id, type, title, content, file_name, description, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$topic_id, $type, $title, $content, $_FILES['file']['name'] ?? '', $description, 'active']);

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

// Search/Filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$typeFilter = isset($_GET['type_filter']) ? $_GET['type_filter'] : '';

$query = "SELECT * FROM topic_content WHERE topic_id = ?";
$params = [$topic_id];

if (!empty($search)) {
    $query .= " AND (title LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($typeFilter)) {
    $query .= " AND type = ?";
    $params[] = $typeFilter;
}

$query .= " ORDER BY sort_order, created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$content_items = $stmt->fetchAll();

// Get statistics
//$totalContent = count($content_items);
//$textContent = $pdo->query("SELECT COUNT(*) as count FROM topic_content WHERE topic_id = ? AND type = 'text'", [$topic_id])->fetch()['count'] ?? 0;
//$imageContent = $pdo->query("SELECT COUNT(*) as count FROM topic_content WHERE topic_id = ? AND type = 'image'", [$topic_id])->fetch()['count'] ?? 0;
//$fileContent = $pdo->query("SELECT COUNT(*) as count FROM topic_content WHERE topic_id = ? AND type = 'file'", [$topic_id])->fetch()['count'] ?? 0;

$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) AS total,
        SUM(type='text') AS text_count,
        SUM(type='image') AS image_count,
        SUM(type='file') AS file_count
    FROM topic_content
    WHERE topic_id = ?
");

$stmt->execute([$topic_id]);
$row = $stmt->fetch();

$totalContent = $row['total'] ?? 0;
$textContent  = $row['text_count'] ?? 0;
$imageContent = $row['image_count'] ?? 0;
$fileContent  = $row['file_count'] ?? 0;

$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM topic_content WHERE topic_id = ? AND type = 'text'");
$stmt->execute([$topic_id]);
$textContent = $stmt->fetch()['count'];

$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM topic_content WHERE topic_id = ? AND type = 'image'");
$stmt->execute([$topic_id]);
$imageContent = $stmt->fetch()['count'];

$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM topic_content WHERE topic_id = ? AND type = 'file'");
$stmt->execute([$topic_id]);
$fileContent = $stmt->fetch()['count'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Content - <?php echo htmlspecialchars($topic['title']); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Favicon -->
<link rel="icon" type="image/png" href="../assets/img/logo.png">
    <style>
        :root {
            --primary: #002366;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #17a2b8;
            --light-bg: #f8f9fa;
            --border-color: #dee2e6;
        }

        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 280px;
            z-index: 100;
            overflow-y: auto;
        }

        .cms-container {
            margin-left: 280px;
            padding: 18px 20px;
            min-height: 100vh;
        }

        /* Header Section */
        .content-header {
            margin-bottom: 18px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-info h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .header-info p {
            color: #6c757d;
            font-size: 13px;
            margin: 0;
        }

        .back-button {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 9px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .back-button:hover {
            background-color: #5a6268;
            color: white;
        }

        /* Statistics Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--primary);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .stat-card.text {
            border-left-color: #3498db;
        }

        .stat-card.image {
            border-left-color: #9b59b6;
        }

        .stat-card.file {
            border-left-color: #e74c3c;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            margin: 8px 0;
        }

        .stat-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-icon {
            font-size: 20px;
            margin-bottom: 8px;
        }

        /* Controls Section */
        .controls-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .search-filter-group {
            display: flex;
            gap: 8px;
            flex: 1;
            max-width: 600px;
        }

        .search-filter-group input,
        .search-filter-group select {
            height: 36px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 0 10px;
            font-size: 13px;
        }

        .search-filter-group input {
            flex: 1;
        }

        .btn-create {
            background-color: var(--primary);
            border: none;
            padding: 9px 16px;
            border-radius: 6px;
            color: white;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
            height: 36px;
        }

        .btn-create:hover {
            background-color: #001d4f;
        }

        /* Table Styling */
        .content-table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
            font-size: 13px;
        }

        .table thead {
            background-color: var(--light-bg);
            border-bottom: 2px solid var(--border-color);
        }

        .table thead th {
            color: var(--primary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding: 12px;
            border: none;
        }

        .table tbody td {
            padding: 12px;
            vertical-align: middle;
            border-color: var(--border-color);
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Content Title */
        .content-title {
            font-weight: 600;
            color: var(--primary);
        }

        .content-description {
            color: #6c757d;
            font-size: 12px;
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Type Badge */
        .type-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 16px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .type-badge.text {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .type-badge.image {
            background-color: #f3e8ff;
            color: #6b21a8;
        }

        .type-badge.file {
            background-color: #fee2e2;
            color: #7f1d1d;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 6px;
            justify-content: center;
        }

        .btn-action {
            padding: 5px 9px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background: white;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }

        .btn-action:hover {
            background-color: var(--light-bg);
        }

        .btn-action.view {
            color: var(--primary);
        }

        .btn-action.delete {
            color: var(--danger);
        }

        .btn-action.delete:hover {
            background-color: #f8d7da;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 30px 20px;
            color: #6c757d;
        }

        .empty-state-icon {
            font-size: 40px;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        .empty-state-text {
            font-size: 15px;
            margin-bottom: 10px;
        }

        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 6px;
            padding: 11px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        /* Modal Styling */
        .modal-header {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 15px;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-title {
            font-weight: 600;
            font-size: 16px;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
        }

        .btn-sm {
            font-size: 12px !important;
            padding: 0.35rem 0.6rem !important;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 6px;
            font-size: 13px;
        }

        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 8px;
            font-size: 13px;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 35, 102, 0.25);
        }

        .mb-3 {
            margin-bottom: 15px;
        }

        .modal .mb-3 {
            margin-bottom: 12px;
        }

        /* Preview */
        .content-preview {
            max-width: 80px;
            max-height: 80px;
            border-radius: 4px;
            object-fit: cover;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .cms-container {
                margin-left: 0;
                padding: 15px;
            }

            .content-header {
                flex-direction: column;
                gap: 10px;
            }

            .header-info h1 {
                font-size: 22px;
            }

            .controls-section {
                flex-direction: column;
                align-items: stretch;
            }

            .search-filter-group {
                max-width: 100%;
                flex-direction: column;
            }

            .search-filter-group input,
            .search-filter-group select {
                width: 100%;
            }

            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .action-buttons {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <?php include_once '../includes/sidebar.php'; ?>

    <div class="cms-container">
        <!-- Header -->
        <div class="content-header">
            <div class="header-info">
                <h1><i class="fas fa-folder"></i> <?php echo htmlspecialchars($topic['title']); ?></h1>
                <p>Manage and organize content for this topic</p>
            </div>
            <a href="cms_topics.php" class="back-button">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
        </div>

        <!-- Success Message -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div>
                    <?php 
                    if ($_GET['success'] == 'added') echo 'Content added successfully!';
                    elseif ($_GET['success'] == 'deleted') echo 'Content deleted successfully!';
                    ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
                <div class="stat-label">Total Content</div>
                <div class="stat-value"><?php echo count($content_items); ?></div>
            </div>
            <div class="stat-card text">
                <div class="stat-icon"><i class="fas fa-align-left"></i></div>
                <div class="stat-label">Text Items</div>
                <div class="stat-value"><?php echo $textContent; ?></div>
            </div>
            <div class="stat-card image">
                <div class="stat-icon"><i class="fas fa-image"></i></div>
                <div class="stat-label">Images</div>
                <div class="stat-value"><?php echo $imageContent; ?></div>
            </div>
            <div class="stat-card file">
                <div class="stat-icon"><i class="fas fa-file"></i></div>
                <div class="stat-label">Files</div>
                <div class="stat-value"><?php echo $fileContent; ?></div>
            </div>
        </div>

        <!-- Controls Section -->
        <div class="controls-section">
            <form method="GET" style="display: flex; gap: 8px; flex: 1; max-width: 600px;">
                <input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">
                <input type="text" name="search" placeholder="Search content..." value="<?php echo htmlspecialchars($search); ?>" class="form-control">
                <select name="type_filter" class="form-control" style="min-width: 140px;">
                    <option value="">All Types</option>
                    <option value="text" <?php echo $typeFilter === 'text' ? 'selected' : ''; ?>>Text</option>
                    <option value="image" <?php echo $typeFilter === 'image' ? 'selected' : ''; ?>>Image</option>
                    <option value="file" <?php echo $typeFilter === 'file' ? 'selected' : ''; ?>>File</option>
                </select>
                <button type="submit" class="btn-create" style="margin: 0;">
                    <i class="fas fa-search"></i> Filter
                </button>
            </form>
            <button class="btn-create" data-bs-toggle="modal" data-bs-target="#addContentModal">
                <i class="fas fa-plus"></i> Add Content
            </button>
        </div>

        <!-- Content Table -->
        <div class="content-table-container">
            <?php if (empty($content_items)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <div class="empty-state-text">No content yet</div>
                    <p style="font-size: 13px; margin-bottom: 0;">Add your first content item to get started</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 8%;">Preview</th>
                                <th style="width: 25%;">Title</th>
                                <th style="width: 35%;">Description</th>
                                <th style="width: 12%;">Type</th>
                                <th style="width: 20%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($content_items as $item): ?>
                                <tr>
                                    <td style="text-align: center;">
                                        <?php if ($item['type'] === 'image'): ?>
                                            <img src="<?php echo htmlspecialchars($item['content']); ?>" class="content-preview" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                        <?php else: ?>
                                            <i class="fas fa-file" style="font-size: 24px; color: #6c757d;"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td><span class="content-title"><?php echo htmlspecialchars($item['title']); ?></span></td>
                                    <td>
                                        <span class="content-description">
                                            <?php echo !empty($item['description']) ? htmlspecialchars($item['description']) : '<em>No description</em>'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="type-badge <?php echo $item['type']; ?>">
                                            <?php echo ucfirst($item['type']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if ($item['type'] === 'image'): ?>
                                                <a href="<?php echo htmlspecialchars($item['content']); ?>" target="_blank" class="btn-action view" title="View Full Size">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            <?php elseif ($item['type'] === 'file'): ?>
                                                <a href="<?php echo htmlspecialchars($item['content']); ?>" download class="btn-action view" title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="cms_content.php?topic_id=<?php echo $topic_id; ?>&delete_content=<?php echo $item['id']; ?>" 
                                               class="btn-action delete" 
                                               onclick="return confirm('Are you sure you want to delete this content?');" 
                                               title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
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
                            <label class="form-label">Content Type <span style="color: red;">*</span></label>
                            <select class="form-control" name="type" id="contentType" required>
                                <option value="">Select Type</option>
                                <option value="text">📝 Text</option>
                                <option value="image">🖼️ Image</option>
                                <option value="file">📎 File</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Title <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="title" placeholder="Enter content title" required>
                        </div>

                        <div class="mb-3" id="textContentDiv" style="display: none;">
                            <label class="form-label">Text Content</label>
                            <textarea class="form-control" name="text_content" rows="5" placeholder="Enter your text content here"></textarea>
                        </div>

                        <div class="mb-3" id="fileContentDiv" style="display: none;">
                            <label class="form-label">File</label>
                            <input type="file" class="form-control" name="file">
                            <small class="text-muted">Images: JPG, PNG, GIF (max 5MB). Files: PDF, DOC, XLS (max 10MB)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2" placeholder="Optional description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding: 12px; gap: 8px;">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Add Content</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script>
        document.getElementById('contentType').addEventListener('change', function() {
            const type = this.value;
            document.getElementById('textContentDiv').style.display = type === 'text' ? 'block' : 'none';
            document.getElementById('fileContentDiv').style.display = (type === 'image' || type === 'file') ? 'block' : 'none';
        });
    </script>
</body>

</html>