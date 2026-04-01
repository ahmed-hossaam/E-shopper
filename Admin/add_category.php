<?php
session_start();
require '../includes/db.php';

// Auth Protection
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

// Handle Add Logic
if (isset($_POST['add_cat'])) {
    $cat_name = trim($_POST['cat_name']);
    if (!empty($cat_name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        if ($stmt->execute([$cat_name])) {
            $message = "Category added successfully!";
        }
    }
}

require_once "sidebar.php";
?>

<main class="add-product-page">
    <div class="page-header">
        <h2>Add New Category</h2>
        <a href="categories.php" class="back-btn"
            style="background: var(--color-primary); padding:10px 20px; color:white; border-radius:10px; text-decoration:none;">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="form-container">
        <?php if($message): ?>
        <div
            style="background: #d1e7dd; color: #0f5132; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border-left: 5px solid #198754;">
            <i class="fas fa-check-circle"></i> <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label>Category Name</label>
                <input type="text" name="cat_name" placeholder="Ex: Electronics, Shoes, etc." required>
            </div>

            <button type="submit" name="add_cat" class="submit-btn">
                <i class="fas fa-save"></i> Save Category
            </button>
        </form>
    </div>
</main>

<?php require_once "footer.php"; ?>