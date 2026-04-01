<?php
session_start();
require_once "../includes/db.php";

// 1. Page Protection
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Delete logic (handled on the same page)
$msg = "";
if (isset($_GET['delete_id'])) {
    $id_to_delete = $_GET['delete_id'];
    
    // Security: Prevent admin from deleting their own account
    if ($id_to_delete != $_SESSION['admin_id']) {
        $delete_stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role = 'admin'");
        if ($delete_stmt->execute([$id_to_delete])) {
            $msg = "Admin deleted successfully!";
        }
    } else {
        $msg = "Error: You cannot delete your own account!";
    }
}

// 3. Fetch data (post-deletion to refresh the table)
$stmt = $conn->query("SELECT id, name, email, created_at FROM users WHERE role = 'admin' ORDER BY id DESC");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "sidebar.php";
?>
<main class="container-content">
    <div class="page-header"
        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2><i class="fas fa-user-shield"></i> Admin Management</h2>
        <a href="add_admin.php" class="btn-primary-custom" style="display:flex;align-items:center ">
            <i class="fas fa-plus" style="font-size:20px"></i> Add New Admin
        </a>
    </div>
    <?php if($msg): ?>
    <div id="alert-msg"
        style="padding: 1rem; background: var(--color-primary); color: white; border-radius: 1rem; margin-bottom: 1.5rem; text-align: center; box-shadow: 0 1rem 2rem var(--color-light);">
        <?= $msg ?>
    </div>
    <script>
    setTimeout(() => {
        document.getElementById('alert-msg').style.display = 'none';
    }, 3000);
    </script>
    <?php endif; ?>
    <div class="recent-orders"
        style="background: var(--color-white); padding: 1.5rem; border-radius: 2rem; box-shadow: 0 2rem 3rem var(--color-light);">
        <table style="width: 100%; text-align: center; border-collapse: collapse;">
            <thead>
                <tr style="height: 3rem; border-bottom: 1px solid var(--color-light);">
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Joined Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($admins as $admin): ?>
                <tr style="height: 3.5rem; border-bottom: 1px solid var(--color-light);">
                    <td>#<?php echo $admin['id']; ?></td>
                    <td><?php echo htmlspecialchars($admin['name']); ?></td>
                    <td><?php echo htmlspecialchars($admin['email']); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($admin['created_at'])); ?></td>
                    <td class="actions-cell">
                        <div class="action-btns">
                            <a href="edit_admin.php?id=<?= $admin['id']; ?>" class="action-icon edit"
                                title="Edit Admin">
                                <i class="fas fa-edit"></i>
                            </a>

                            <?php if ($admin['id'] != $_SESSION['admin_id']): ?>
                            <a href="admins.php?delete_id=<?= $admin['id']; ?>" class="action-icon delete"
                                title="Delete Admin"
                                onclick="return confirm('Are you sure you want to delete this admin?')">
                                <i class="fas fa-trash"></i>
                            </a>
                            <?php else: ?>
                            <span class="me-badge">You</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<style>
.btn-primary-custom {
    background: var(--color-primary);
    color: white;
    padding: 0.8rem 1.5rem;
    border-radius: 1rem;
    font-weight: 600;
    transition: 0.3s;
}

.btn-primary-custom:hover {
    box-shadow: 0 1rem 2rem rgba(115, 128, 236, 0.2);
    transform: translateY(-2px);
}

.container-content {
    padding: 2rem;
}

/* Cell and container formatting */
.actions-cell {
    display: flex;
    justify-content: center;
    align-items: center;
}

.action-btns {
    display: flex;
    gap: 8px;
    /* Space between icons */
    align-items: center;
}

/* Unified icon style */
.action-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    /* Soft edges */
    font-size: 1px;
    transition: all 0.3s ease;
    cursor: pointer;
}

/* Edit button colors */
.action-icon.edit {
    color: var(--color-primary);
}


.action-icon.delete {
    color: #ff7782;
}


/* Current account / "You" badge style */
.me-badge {
    background: var(--color-light);
    color: var(--color-info-dark);
    padding: 4px 10px;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

i {
    font-size: 20px;
}
</style>

<?php require_once "footer.php"; ?>