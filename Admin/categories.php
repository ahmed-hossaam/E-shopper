<style>
/* --- Categories Table Custom Styles --- */

/* Ensures the table fills the container and maintains spacing */
.categories-table {
    width: 100% !important;
    border-collapse: collapse;
    margin: 1rem 0;
}

.categories-table th,
.categories-table td {
    padding: 1.2rem 1rem;
    text-align: left;
    vertical-align: middle;
}

/* Category name styling for better readability */
.cat-name {
    font-weight: 600;
    color: #333;
}

/* Action buttons container styling */
.action-btns {
    display: flex;
    gap: 10px;
    justify-content: center;
}

/* Generic style for all action icons */
.action-btns a {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: 0.3s opacity;
}

.action-btns a:hover {
    opacity: 0.8;
}

/* Button specific colors */
.view-btn {
    background: #0dcaf0;
}

.edit-btn {
    background: #ffbb55;
}

.delete-btn {
    background: #ff7782;
}

/* Status message (Alert) styling */
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

// Authentication: Ensure user is logged in as Admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$msg = "";
$msg_type = "";

// --- Handle Category Deletion ---
if (isset($_GET['delete_id'])) {
    $id_to_delete = (int)$_GET['delete_id'];
    
    try {
        $delete_stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        if ($delete_stmt->execute([$id_to_delete])) {
            $msg = "Category deleted successfully!";
            $msg_type = "success";
        }
    } catch (PDOException $e) {
        // Error handling for foreign key constraints (e.g., category linked to products)
        $msg = "Error: Cannot delete category. It might be linked to existing products.";
        $msg_type = "danger";
    }
}

// Fetch all categories sorted by most recent
$query = "SELECT * FROM categories ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "sidebar.php";
?>

<main class="categories-page">
    <div class="page-header">
        <h2>Categories List</h2>
        <a href="add_category.php" class="add-btn"
            style="background: #7380ec; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
            <i class="fas fa-plus"></i> Add New Category
        </a>
    </div>

    <?php if ($msg != ""): ?>
    <div class="alert alert-<?= $msg_type ?>">
        <i class="fas <?= $msg_type == 'danger' ? 'fa-exclamation-circle' : 'fa-check-circle' ?>"
            style="margin-right: 8px;"></i>
        <?= $msg ?>
    </div>
    <?php endif; ?>

    <div class="card-container"
        style="background: white; padding: 2rem; border-radius: 2rem; box-shadow: 0 2rem 3rem rgba(132, 139, 200, 0.18); transition: all 300ms ease; margin-top: 1rem;">
        <div class="table-responsive">
            <table class="categories-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Category Name</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categories as $cat): ?>
                    <tr>
                        <td>#<?= $cat['id']; ?></td>
                        <td class="cat-name"><?= htmlspecialchars($cat['name']); ?></td>
                        <td>
                            <span style="color: #888; font-size: 0.85rem;">
                                <i class="far fa-calendar-alt"></i>
                                <?= date('d M Y', strtotime($cat['created_at'] ?? 'now')); ?>
                            </span>
                        </td>
                        <td class="action-btns">
                            <a href="view_category.php?id=<?= $cat['id']; ?>" class="view-btn" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="edit_category.php?id=<?= $cat['id']; ?>" class="edit-btn" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="categories.php?delete_id=<?= $cat['id']; ?>" class="delete-btn"
                                onclick="return confirm('Are you sure you want to delete this category?')"
                                title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once "footer.php"; ?>