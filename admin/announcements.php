<?php
session_start();
require_once '../db_function/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add_announcement'])) {

        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        $status = $_POST['status'];
        $featured = isset($_POST['is_featured']) ? 1 : 0;

        $image_path = '';

        if (!empty($_FILES['image']['name'])) {
            $dir = "../uploads/";
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $file = time() . "_" . basename($_FILES["image"]["name"]);
            $target = $dir . $file;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target)) {
                $image_path = "uploads/" . $file;
            }
        }

        $stmt = $pdo->prepare("INSERT INTO announcements (title, content, image, status, is_featured, created_by)
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $content, $image_path, $status, $featured, $_SESSION['user_id']]);

        $message = "Announcement created successfully!";
        $messageType = "success";
    }

    if (isset($_POST['delete_announcement'])) {
        $stmt = $pdo->prepare("DELETE FROM announcements WHERE id=?");
        $stmt->execute([$_POST['announcement_id']]);

        $message = "Deleted successfully!";
        $messageType = "success";
    }
}

$announcements = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Announcements</title>

<link rel="stylesheet" href="../assets/css/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
:root{
    --primary:#002366;
}

body{
    background:#f5f7fa;
}

/* layout */
.sidebar{
    position:fixed;
    width:260px;
    height:100vh;
}

.cms-container{
    margin-left:260px;
    padding:20px;
}

/* header */
.page-title{
    font-size:26px;
    font-weight:700;
    color:var(--primary);
}
.page-subtitle{
    font-size:13px;
    color:#6c757d;
}

/* cards */
.card-box{
    background:#fff;
    border-radius:8px;
    box-shadow:0 2px 6px rgba(0,0,0,.08);
    padding:20px;
    margin-bottom:20px;
}

/* table */
.table-container{
    background:#fff;
    border-radius:8px;
    box-shadow:0 2px 6px rgba(0,0,0,.08);
}

/* badge */
.badge.featured{
    background:#ffc107;
    color:#000;
}
.badge.published{
    background:#28a745;
}
.badge.draft{
    background:#6c757d;
}
.badge.archived{
    background:#dc3545;
}

/* image */
.thumb{
    width:70px;
    height:50px;
    object-fit:cover;
    border-radius:6px;
}
</style>
</head>

<body>

<?php include_once '../includes/sidebar.php'; ?>

<div class="cms-container">

    <!-- HEADER -->
    <div>
        <h1 class="page-title">Announcements</h1>
        <p class="page-subtitle">Create and manage system announcements</p>
    </div>

    <!-- ALERT -->
    <?php if($message): ?>
        <div class="alert alert-<?= $messageType ?> mt-3">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <!-- FORM -->
    <div class="card-box mt-3">
        <h5>Add Announcement</h5>

        <form method="POST" enctype="multipart/form-data" class="row g-2">

            <div class="col-12">
                <input class="form-control" name="title" placeholder="Title" required>
            </div>

            <div class="col-12">
                <textarea class="form-control" name="content" rows="4" placeholder="Content..." required></textarea>
            </div>

            <div class="col-md-4">
                <input type="file" class="form-control" name="image">
            </div>

            <div class="col-md-4">
                <select class="form-control" name="status">
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="archived">Archived</option>
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-center">
                <label>
                    <input type="checkbox" name="is_featured"> Featured
                </label>
            </div>

            <div class="col-12">
                <button class="btn btn-primary" name="add_announcement">
                    Save Announcement
                </button>
            </div>

        </form>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Date</th>
                        <th style="width:120px;">Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php foreach($announcements as $a): ?>
                <tr>
                    <td>
                        <?php if($a['image']): ?>
                            <img src="../<?= $a['image'] ?>" class="thumb">
                        <?php endif; ?>
                    </td>

                    <td><b><?= htmlspecialchars($a['title']) ?></b></td>

                    <td>
                        <span class="badge <?= $a['status'] ?>">
                            <?= ucfirst($a['status']) ?>
                        </span>
                    </td>

                    <td>
                        <?= $a['is_featured'] ? '<span class="badge featured">Yes</span>' : '-' ?>
                    </td>

                    <td><?= date('M d, Y', strtotime($a['created_at'])) ?></td>

                    <td>
                        <form method="POST">
                            <input type="hidden" name="announcement_id" value="<?= $a['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger"
                                    name="delete_announcement"
                                    onclick="return confirm('Delete this announcement?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>