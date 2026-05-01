<?php
session_start();
require_once '../db_function/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$message = '';
$messageType = '';

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

// Handle topic update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_topic'])) {
    $topic_id = intval($_POST['topic_id']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];

    if (!empty($title)) {
        $stmt = $pdo->prepare("UPDATE topics SET title = ?, description = ?, status = ? WHERE id = ?");
        $stmt->execute([$title, $description, $status, $topic_id]);

        header("Location: cms_topics.php?success=updated");
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

// Handle status change
if (isset($_GET['change_status']) && isset($_GET['topic_id']) && is_numeric($_GET['topic_id'])) {
    $topic_id = intval($_GET['topic_id']);
    $new_status = $_GET['change_status'];
    if (in_array($new_status, ['published', 'draft', 'archived'])) {
        $stmt = $pdo->prepare("UPDATE topics SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $topic_id]);
        header("Location: cms_topics.php?success=status_changed");
        exit();
    }
}

// Search/Filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';

$query = "SELECT t.*, COUNT(tc.id) as content_count FROM topics t LEFT JOIN topic_content tc ON t.id = tc.topic_id WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (t.title LIKE ? OR t.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($statusFilter)) {
    $query .= " AND t.status = ?";
    $params[] = $statusFilter;
}

$query .= " GROUP BY t.id ORDER BY t.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$topics = $stmt->fetchAll();

// Get statistics
$totalTopics = $pdo->query("SELECT COUNT(*) as count FROM topics")->fetch()['count'];
$publishedTopics = $pdo->query("SELECT COUNT(*) as count FROM topics WHERE status = 'published'")->fetch()['count'];
$draftTopics = $pdo->query("SELECT COUNT(*) as count FROM topics WHERE status = 'draft'")->fetch()['count'];
$archivedTopics = $pdo->query("SELECT COUNT(*) as count FROM topics WHERE status = 'archived'")->fetch()['count'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Management - People's Bank</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

        /* Ensure sidebar is fixed */
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
        .topics-header {
            margin-bottom: 18px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 3px;
        }

        .page-subtitle {
            color: #6c757d;
            font-size: 13px;
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

        .stat-card.published {
            border-left-color: var(--success);
        }

        .stat-card.draft {
            border-left-color: var(--warning);
        }

        .stat-card.archived {
            border-left-color: var(--danger);
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
        .topics-table-container {
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

        /* Topic Title */
        .topic-title {
            font-weight: 600;
            color: var(--primary);
        }

        .topic-description {
            color: #6c757d;
            font-size: 12px;
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 16px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-badge.published {
            background-color: #d4edda;
            color: #155724;
        }

        .status-badge.draft {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-badge.archived {
            background-color: #f8d7da;
            color: #721c24;
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

        .btn-action.edit {
            color: #17a2b8;
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

        .btn-action.manage {
            color: #17a2b8;
            background-color: #e0f7fa;
            border-color: #b2ebf2;
            font-weight: 600;
        }

        .btn-action.manage:hover {
            background-color: #b2ebf2;
            border-color: #80deea;
            color: #00838f;
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

        /* Responsive */
        @media (max-width: 768px) {
            .cms-container {
                margin-left: 0;
                padding: 15px;
            }

            .page-title {
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
        <div class="topics-header">
            <h1 class="page-title">Report Management</h1>
            <p class="page-subtitle">Create, edit, and organize your reports</p>
        </div>

        <!-- Success Message -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div>
                    <?php 
                    if ($_GET['success'] == 'created') echo 'Topic created successfully!';
                    elseif ($_GET['success'] == 'updated') echo 'Topic updated successfully!';
                    elseif ($_GET['success'] == 'deleted') echo 'Topic deleted successfully!';
                    elseif ($_GET['success'] == 'status_changed') echo 'Topic status updated!';
                    ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
                <div class="stat-label">Total Reports</div>
                <div class="stat-value"><?php echo $totalTopics; ?></div>
            </div>
            <div class="stat-card published">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-label">Published</div>
                <div class="stat-value"><?php echo $publishedTopics; ?></div>
            </div>
            <div class="stat-card draft">
                <div class="stat-icon"><i class="fas fa-pencil-alt"></i></div>
                <div class="stat-label">Draft</div>
                <div class="stat-value"><?php echo $draftTopics; ?></div>
            </div>
            <div class="stat-card archived">
                <div class="stat-icon"><i class="fas fa-archive"></i></div>
                <div class="stat-label">Archived</div>
                <div class="stat-value"><?php echo $archivedTopics; ?></div>
            </div>
        </div>

        <!-- Controls Section -->
        <div class="controls-section">
            <form method="GET" style="display: flex; gap: 8px; flex: 1; max-width: 600px;">
                <input type="text" name="search" placeholder="Search reports..." value="<?php echo htmlspecialchars($search); ?>" class="form-control">
                <select name="status_filter" class="form-control" style="min-width: 140px;">
                    <option value="">All Status</option>
                    <option value="published" <?php echo $statusFilter === 'published' ? 'selected' : ''; ?>>Published</option>
                    <option value="draft" <?php echo $statusFilter === 'draft' ? 'selected' : ''; ?>>Draft</option>
                    <option value="archived" <?php echo $statusFilter === 'archived' ? 'selected' : ''; ?>>Archived</option>
                </select>
                <button type="submit" class="btn-create" style="margin: 0;">
                    <i class="fas fa-search"></i> Filter
                </button>
            </form>
            <button class="btn-create" data-bs-toggle="modal" data-bs-target="#createTopicModal">
                <i class="fas fa-plus"></i> New Report
            </button>
        </div>

        <!-- Topics Table -->
        <div class="topics-table-container">
            <?php if (empty($topics)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <div class="empty-state-text">No Report found</div>
                    <p style="font-size: 13px; margin-bottom: 0;">Create your first report to get started</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Title</th>
                                <th style="width: 35%;">Description</th>
                                <th style="width: 10%;">Status</th>
                                <th style="width: 8%;">Content</th>
                                <th style="width: 22%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topics as $topic): ?>
                                <tr>
                                    <td><span class="topic-title"><?php echo htmlspecialchars($topic['title']); ?></span></td>
                                    <td>
                                        <span class="topic-description">
                                            <?php echo !empty($topic['description']) ? htmlspecialchars($topic['description']) : '<em>No description</em>'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo $topic['status']; ?>">
                                            <?php echo ucfirst($topic['status']); ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <span style="background-color: var(--light-bg); padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 12px;">
                                            <?php echo $topic['content_count']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action view" data-bs-toggle="modal" data-bs-target="#viewTopicModal<?php echo $topic['id']; ?>" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn-action edit" data-bs-toggle="modal" data-bs-target="#editTopicModal<?php echo $topic['id']; ?>" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="cms_content.php?topic_id=<?php echo $topic['id']; ?>" class="btn-action manage" title="Manage Content">
                                                <i class="fas fa-folder-open"></i> Manage
                                            </a>
                                            <a href="cms_topics.php?delete=<?php echo $topic['id']; ?>" class="btn-action delete" onclick="return confirm('Are you sure you want to delete this report and its content?');" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                <!-- View Modal -->
                                <div class="modal fade" id="viewTopicModal<?php echo $topic['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Report Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Title</label>
                                                    <p><?php echo htmlspecialchars($topic['title']); ?></p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <p><?php echo htmlspecialchars($topic['description'] ?? 'N/A'); ?></p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <p><span class="status-badge <?php echo $topic['status']; ?>"><?php echo ucfirst($topic['status']); ?></span></p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Created</label>
                                                    <p><?php echo date('M d, Y \a\t h:i A', strtotime($topic['created_at'])); ?></p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Last Updated</label>
                                                    <p><?php echo date('M d, Y \a\t h:i A', strtotime($topic['updated_at'])); ?></p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Associated Content</label>
                                                    <p><?php echo $topic['content_count']; ?> item(s)</p>
                                                </div>
                                            </div>
                                            <div class="modal-footer" style="padding: 12px; gap: 8px;">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editTopicModal<?php echo $topic['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Report</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body">
                                                    <input type="hidden" name="topic_id" value="<?php echo $topic['id']; ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label">Report Title</label>
                                                        <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($topic['title']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Description</label>
                                                        <textarea class="form-control" name="description" rows="3"><?php echo htmlspecialchars($topic['description'] ?? ''); ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Status</label>
                                                        <select class="form-control" name="status">
                                                            <option value="draft" <?php echo $topic['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                                            <option value="published" <?php echo $topic['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                                                            <option value="archived" <?php echo $topic['status'] === 'archived' ? 'selected' : ''; ?>>Archived</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer" style="padding: 12px; gap: 8px;">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" name="update_topic" class="btn btn-primary btn-sm">Update Report</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Create Topic Modal -->
    <div class="modal fade" id="createTopicModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Report Title <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" name="title" placeholder="Enter report title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="4" placeholder="Enter report description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Initial Status</label>
                            <select class="form-control" name="status">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding: 12px; gap: 8px;">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="create_topic" class="btn btn-primary btn-sm">Create Report</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>

</html>