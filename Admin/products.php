<?php
session_start();
require '../includes/db.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
// Handle Delete Product Logic
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Optional: Delete physical image file if needed
    $del_stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $del_stmt->execute([$id]);
    header("Location: products.php");
    exit();
}

// Fetch products
$query = "SELECT * FROM products ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "sidebar.php";
?>

<main class="products-page">
    <div class="page-header">
        <h2>Products List</h2>
        <a href="add-product.php" class="add-btn"><i class="fas fa-plus"></i> Add Product</a>
    </div>
    <div class="custom-card">
        <div class="table-responsive">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products as $product): 
                        // Fetch category name per product
                        $cat_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
                        $cat_stmt->execute([$product['category_id']]);
                        $category_name = $cat_stmt->fetchColumn() ?: 'Uncategorized';
                    ?>
                    <tr>
                        <td><img src="../img/<?= explode('-', $product['image'])[0] ?>/<?= $product['image'] ?>"
                                width="40" style="border-radius:5px;"></td>
                        <td class="product-name"><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($category_name) ?></td>
                        <td>$<?= number_format($product['price'], 2) ?></td>
                        <td>
                            <span class="status-badge <?= $product['stock'] > 5 ? 'available' : 'low-stock' ?>">
                                <?= $product['stock'] > 5 ? 'Available' : 'Low Stock' ?> (<?= $product['stock'] ?>)
                            </span>
                        </td>
                        <td class="action-btns text-center">
                            <a href="product_details.php?id=<?= $product['id'] ?>" class="view-btn"><i
                                    class="fas fa-eye"></i></a>
                            <a href="edit_product.php?id=<?= $product['id'] ?>" class="edit-btn"><i
                                    class="fas fa-edit"></i></a>
                            <a href="?delete=<?= $product['id'] ?>" class="delete-btn"
                                onclick="return confirm('Delete product?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>