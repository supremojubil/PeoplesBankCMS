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

    if (isset($_POST['add_story'])) {
        $name = trim($_POST['name']);
        $position = trim($_POST['position']);
        $story = trim($_POST['story']);
        $status = $_POST['status'];

        if ($name && $story) {
            try {
                $stmt = $pdo->prepare("INSERT INTO member_stories (name, position, story, status, created_by) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $position, $story, $status, $_SESSION['user_id']]);

                $message = "Member story added successfully!";
                $messageType = "success";
            } catch (Exception $e) {
                $message = "Error: " . $e->getMessage();
                $messageType = "danger";
            }
        }
    }

    if (isset($_POST['edit_story'])) {
        $stmt = $pdo->prepare("UPDATE member_stories SET name=?, position=?, story=?, status=? WHERE id=?");
        $stmt->execute([
            $_POST['name'],
            $_POST['position'],
            $_POST['story'],
            $_POST['status'],
            $_POST['story_id']
        ]);

        $message = "Updated successfully!";
        $messageType = "success";
    }

    if (isset($_POST['delete_story'])) {
        $stmt = $pdo->prepare("DELETE FROM member_stories WHERE id=?");
        $stmt->execute([$_POST['story_id']]);

        $message = "Deleted successfully!";
        $messageType = "success";
    }
}

$stmt = $pdo->query("SELECT * FROM member_stories ORDER BY created_at DESC");
$stories = $stmt->fetchAll();

$editStory = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM member_stories WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editStory = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Member Stories</title>

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

    /* card */
    .card-box{
        background:#fff;
        border-radius:8px;
        box-shadow:0 2px 6px rgba(0,0,0,.08);
        padding:20px;
    }

    /* table */
    .table-container{
        background:#fff;
        border-radius:8px;
        box-shadow:0 2px 6px rgba(0,0,0,.08);
    }

    /* form */
    .form-control{
        border-radius:6px;
    }

    /* buttons */
    .btn-primary{
        background:var(--primary);
        border:none;
    }
    .btn-outline-primary{
        border-color:var(--primary);
        color:var(--primary);
    }
    .btn-outline-primary:hover{
        background:var(--primary);
        color:#fff;
    }

    .badge.active{
        background:#28a745;
    }
    .badge.inactive{
        background:#6c757d;
    }
    </style>
</head>

<body>

<?php include_once '../includes/sidebar.php'; ?>

<div class="cms-container">

    <!-- HEADER -->
    <div>
        <h1 class="page-title">Member Stories</h1>
        <p class="page-subtitle">Manage member testimonials displayed on website</p>
    </div>

    <!-- ALERT -->
    <?php if($message): ?>
        <div class="alert alert-<?= $messageType ?> mt-3">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <!-- FORM -->
    <div class="card-box mt-3 mb-4">
        <h5 class="mb-3">
            <?= $editStory ? 'Edit Story' : 'Add New Story' ?>
        </h5>

        <form method="POST" class="row g-2">

            <?php if($editStory): ?>
                <input type="hidden" name="story_id" value="<?= $editStory['id'] ?>">
            <?php endif; ?>

            <div class="col-md-4">
                <input class="form-control" name="name" placeholder="Name"
                       value="<?= $editStory['name'] ?? '' ?>" required>
            </div>

            <div class="col-md-4">
                <input class="form-control" name="position" placeholder="Position"
                       value="<?= $editStory['position'] ?? '' ?>">
            </div>

            <div class="col-md-4">
                <select class="form-control" name="status">
                    <option value="active" <?= ($editStory['status'] ?? '')=='active'?'selected':'' ?>>Active</option>
                    <option value="inactive" <?= ($editStory['status'] ?? '')=='inactive'?'selected':'' ?>>Inactive</option>
                </select>
            </div>

            <div class="col-12">
                <textarea class="form-control" name="story" rows="3"
                          placeholder="Story..." required><?= $editStory['story'] ?? '' ?></textarea>
            </div>

            <div class="col-12">
                <button class="btn btn-primary" name="<?= $editStory?'edit_story':'add_story' ?>">
                    Save
                </button>

                <?php if($editStory): ?>
                    <a href="members.php" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
            </div>

        </form>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Story</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th style="width:160px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach($stories as $s): ?>
                    <tr>
                        <td><b><?= htmlspecialchars($s['name']) ?></b></td>
                        <td><?= htmlspecialchars($s['position']) ?></td>
                        <td><?= substr(htmlspecialchars($s['story']),0,60) ?>...</td>
                        <td>
                            <span class="badge <?= $s['status'] ?>">
                                <?= ucfirst($s['status']) ?>
                            </span>
                        </td>
                        <td><?= date('M d, Y', strtotime($s['created_at'])) ?></td>
                        <td>
                            <a href="?edit=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>

                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="story_id" value="<?= $s['id'] ?>">
                                <button class="btn btn-sm btn-outline-danger" name="delete_story"
                                        onclick="return confirm('Delete this story?')">
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