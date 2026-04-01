<?php
session_start();
require '../includes/db.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_GET['id'])) { header("Location: categories.php"); exit(); }
$cat_id = (int)$_GET['id'];

// 1. Get Category Name
$stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$stmt->execute([$cat_id]);
$cat_name = $stmt->fetchColumn();

// 2. Get Products in this category
$p_stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ?");
$p_stmt->execute([$cat_id]);
$products = $p_stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "sidebar.php";
?>

<main class="view-category-page">
    <div class="page-header">
        <h2>Products in: <span style="color: var(--color-primary);"><?= htmlspecialchars($cat_name); ?></span></h2>
        <a href="categories.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
    </div>

    <div class="custom-card" style="background: white; border-radius: 2rem; padding: 1.5rem;">
        <?php if(count($products) > 0): ?>
        <table class="orders-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $p): 
                    $img = $p['image'];
                    $folder = explode('-', $img)[0];
                    $path = "../img/$folder/$img";
                ?>
                <tr>
                    <td><img src="<?= $path; ?>"
                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;"></td>
                    <td><b><?= htmlspecialchars($p['name']); ?></b></td>
                    <td>$<?= number_format($p['price'], 2); ?></td>
                    <td><?= $p['stock']; ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $p['id']; ?>" style="color: var(--color-primary);"><i
                                class="fas fa-edit"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p style="text-align: center; padding: 20px; color: var(--color-info-dark);">No products found in this category
            yet.</p>
        <?php endif; ?>
    </div>
</main>