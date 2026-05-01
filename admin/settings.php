<?php
session_start();
require_once '../db_function/db.php';

// Only check login (no role restriction for now)
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch users
$stmt = $pdo->query("SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

// Stats
$totalUsers = count($users);
$totalAdmins = count(array_filter($users, fn($u) => $u['role'] === 'admin'));
$totalRegular = $totalUsers - $totalAdmins;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Management</title>

<link rel="stylesheet" href="../assets/css/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
:root {
    --primary:#002366;
    --success:#28a745;
    --warning:#ffc107;
    --danger:#dc3545;
}

body {
    background:#f5f7fa;
}

/* Layout */
.sidebar {
    position: fixed;
    width: 260px;
    height: 100vh;
}

.cms-container {
    margin-left:260px;
    padding:20px;
}

/* Header */
.page-title {
    font-size:26px;
    font-weight:700;
    color:var(--primary);
}
.page-subtitle {
    font-size:13px;
    color:#6c757d;
}

/* Stats */
.stats {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
    gap:15px;
    margin:20px 0;
}
.stat-card {
    background:#fff;
    padding:15px;
    border-radius:8px;
    border-left:4px solid var(--primary);
}
.stat-card.admin { border-color:var(--success); }
.stat-card.user { border-color:var(--warning); }

.stat-value {
    font-size:22px;
    font-weight:bold;
}

/* Table */
.table-container {
    background:#fff;
    border-radius:8px;
    box-shadow:0 2px 6px rgba(0,0,0,.1);
}

.badge-role.admin { background:#002366; }
.badge-role.user { background:#6c757d; }

/* Buttons */
.btn-create {
    background:var(--primary);
    color:#fff;
    border:none;
    padding:8px 14px;
    border-radius:6px;
}
</style>
</head>

<body>

<?php include_once '../includes/sidebar.php'; ?>

<div class="cms-container">

    <!-- HEADER -->
    <div>
        <h1 class="page-title">User Management</h1>
        <p class="page-subtitle">Manage system users and permissions</p>
    </div>

    <!-- STATS -->
    <div class="stats">
        <div class="stat-card">
            <div>Total Users</div>
            <div class="stat-value"><?= $totalUsers ?></div>
        </div>

        <div class="stat-card admin">
            <div>Admins</div>
            <div class="stat-value"><?= $totalAdmins ?></div>
        </div>

        <div class="stat-card user">
            <div>Regular Users</div>
            <div class="stat-value"><?= $totalRegular ?></div>
        </div>
    </div>

    <!-- ACTION -->
    <div class="d-flex justify-content-between mb-3">
        <div></div>
        <button class="btn-create" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-plus"></i> Add User
        </button>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th style="width:150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach ($users as $user): ?>
                <tr>
                    <td><b><?= htmlspecialchars($user['username']) ?></b></td>
                    <td><?= htmlspecialchars($user['email'] ?? '-') ?></td>
                    <td>
                        <span class="badge badge-role <?= $user['role'] ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </td>
                    <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#editUser<?= $user['id'] ?>">
                            Edit
                        </button>
                    </td>
                </tr>

                <!-- EDIT MODAL -->
                <div class="modal fade" id="editUser<?= $user['id'] ?>">
                    <div class="modal-dialog">
                        <form method="POST" class="modal-content">
                            <div class="modal-header">
                                <h5>Edit User</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">

                                <input class="form-control mb-2" name="username"
                                       value="<?= htmlspecialchars($user['username']) ?>">

                                <input class="form-control mb-2" name="email"
                                       value="<?= htmlspecialchars($user['email']) ?>">

                                <select class="form-control" name="role">
                                    <option value="user" <?= $user['role']=='user'?'selected':'' ?>>User</option>
                                    <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-primary" name="edit_user">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ADD MODAL -->
<div class="modal fade" id="addUserModal">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">

            <div class="modal-header">
                <h5>Add User</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input class="form-control mb-2" name="username" placeholder="Username" required>
                <input class="form-control mb-2" name="email" placeholder="Email">
                <input type="password" class="form-control mb-2" name="password" placeholder="Password" required>

                <select class="form-control" name="role">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" name="add_user">Create</button>
            </div>

        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>