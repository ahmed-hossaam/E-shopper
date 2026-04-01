<style>
/* --- Users Table Custom Styles --- */

/* Ensures the table fills the entire card container */
.table {
    width: 100% !important;
    border-collapse: collapse;
    margin: 1rem 0;
}

/* Row and cell padding for a spacious look */
.table th,
.table td {
    padding: 1.2rem 1rem;
    text-align: left;
    vertical-align: middle;
}

/* Min-width to prevent name and email from overlapping on small screens */
.table td:nth-child(2),
.table td:nth-child(3) {
    min-width: 150px;
}

/* Helper class to center-align the actions column */
.text-center {
    text-align: center !important;
}

/* Clean hover effect on table rows */
.table tbody tr:hover {
    background-color: #f8faff;
}

/* Styling for Status Messages (Success/Error) */
.alert {
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 0.8rem;
    font-weight: 500;
}

.alert-danger {
    background: #fff5f5;
    color: #e03131;
    border: 1px solid #ffc9c9;
}

.alert-success {
    background: #ebfbee;
    color: #2f9e44;
    border: 1px solid #b2f2bb;
}
</style>

<?php
session_start();
require '../includes/db.php';

// Authentication Check: Admin must be logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$msg = "";
$msg_type = "";

// Handle User Deletion Logic
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Security Guard: Prevent admin from deleting their own account
    if ($id == $_SESSION['admin_id']) {
        $msg = "Error: You cannot delete your current session account!";
        $msg_type = "danger";
    } else {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt->execute([$id])) {
            $msg = "User has been removed successfully.";
            $msg_type = "success";
        }
    }
}

// Fetch all users sorted by latest registration
$users = $conn->query("SELECT * FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

require_once "sidebar.php";
?>

<main class="orders-page">
    <div class="page-header">
        <h2>Users List</h2>
    </div>

    <?php if ($msg != ""): ?>
    <div class="alert alert-<?= $msg_type ?>">
        <i class="fas <?= $msg_type == 'danger' ? 'fa-exclamation-circle' : 'fa-check-circle' ?>"
            style="margin-right: 8px;"></i>
        <?= $msg ?>
    </div>
    <?php endif; ?>

    <div class="custom-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Avatar</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Joined Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): 
                        // Logic to construct user full name or fallback to email
                        $name = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: $user['email'];
                    ?>
                    <tr>
                        <td>
                            <div class="user-avatar-placeholder"
                                style="background: #7380ec; color: #fff; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.9rem;">
                                <?= strtoupper(substr($name, 0, 1)) ?>
                            </div>
                        </td>
                        <td style="font-weight: 600; color: #333;"><?= htmlspecialchars($name) ?></td>
                        <td style="color: #666;"><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['mobile'] ?? 'N/A') ?></td>
                        <td>
                            <span style="color: #888; font-size: 0.85rem;">
                                <i class="far fa-calendar-alt" style="margin-right: 5px;"></i>
                                <?= date('d M Y', strtotime($user['created_at'])) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="?delete=<?= $user['id'] ?>"
                                style="background: #ff7782; color: white; padding: 8px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; transition: 0.3s;"
                                onclick="return confirm('Are you sure you want to delete this user?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>