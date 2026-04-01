<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include "sidebar.php"; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) { die("Product Not Found!"); }
// Fetch Category Name
    $cat_query = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $cat_query->execute([$product['category_id']]);
    $category = $cat_query->fetch();
    $category_name = ($category) ? $category['name'] : 'Uncategorized';
// Image logic based on folder structure
    $imageName = $product['image']; 
    $folder = explode('-', $imageName)[0];
    $imagePath = "../img/$folder/$imageName";
}
?>

<main class="product-details-page">
    <div class="page-header">
        <h2>Product Details</h2>
        <div class="actions">
            <a href="products.php" class="btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn-warning"><i class="fas fa-edit"></i>
                Edit</a>
        </div>
    </div>

    <div class="details-card">
        <div class="product-info-grid">
            <div class="info-image">
                <img src="<?php echo $imagePath; ?>" alt="">
            </div>

            <div class="info-content">
                <div class="info-item">
                    <label>Product Name</label>
                    <p><?php echo $product['name']; ?></p>
                </div>
                <div class="info-row">
                    <div class="info-item">
                        <label>Category</label>
                        <p><?php echo $category_name; ?></p>
                    </div>
                    <div class="info-item">
                        <label>Price</label>
                        <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                    </div>
                </div>
                <div class="info-item">
                    <label>Stock Quantity</label>
                    <p><?php echo $product['stock']; ?> Pieces</p>
                </div>
                <div class="info-item">
                    <label>Description</label>
                    <p class="desc"><?php echo nl2br($product['description']); ?></p>
                </div>
            </div>
        </div>
    </div>
</main>