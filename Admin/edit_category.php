<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) { header("Location: categories.php"); exit(); }
$id = $_GET['id'];

// Fetch current category data
$stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) { die("Category not found!"); }

$success_msg = "";
if (isset($_POST['update_cat'])) {
    $new_name = trim($_POST['cat_name']);
    $update = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    if ($update->execute([$new_name, $id])) {
        $success_msg = "Category updated successfully!";
        $category['name'] = $new_name; // Update local variable to show new name immediately
    }
}

require_once "sidebar.php";
?>

<main class="add-product-page">
    <div class="page-header">
        <h2>Edit Category: <span
                style="color: var(--color-primary);"><?php echo htmlspecialchars($category['name']); ?></span></h2>
        <a href="categories.php" class="back-btn"
            style="background: var(--color-primary); padding:10px 20px; color:white; border-radius:10px; text-decoration:none;">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="form-container">
        <?php if($success_msg): ?>
        <div
            style="background: #d1e7dd; color: #0f5132; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border-left: 5px solid #198754;">
            <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label>New Category Name</label>
                <input type="text" name="cat_name" value="<?php echo htmlspecialchars($category['name']); ?>" required>
            </div>

            <button type="submit" name="update_cat" class="submit-btn">
                <i class="fas fa-sync-alt"></i> Update Category Name
            </button>
        </form>
    </div>
</main>

<?php require_once "footer.php"; ?>