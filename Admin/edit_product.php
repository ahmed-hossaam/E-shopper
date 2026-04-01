<?php
session_start();
require '../includes/db.php';

// 1. Fetch data for the product to be edited
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        die("Product not found!");
    }
} else {
    header("Location: products.php");
    exit();
}

// 2. Fetch categories for the Select dropdown and validation
$cat_stmt = $conn->prepare("SELECT * FROM categories ORDER BY name ASC");
$cat_stmt->execute();
$all_categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

$error_msg = ""; 
$success_msg = "";

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category'];
    
    // Fetch the name of the selected category to compare with the image name
    $check_cat = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $check_cat->execute([$category_id]);
    $selected_category_name = strtolower($check_cat->fetchColumn());

    $image = $_FILES['image']['name'];
    $target_image = $product['image']; // Default to existing image

    if (!empty($image)) {
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_size = $_FILES['image']['size'];
        
        // Image validation logic
        $image_prefix = strtolower(explode('-', $image)[0]); // First part of the image name

        // Condition: The first part of the image name must match the selected category name
        if ($image_prefix !== $selected_category_name) {
            $error_msg = "Error: Image name must start with '" . $selected_category_name . "-' (Ex: " . $selected_category_name . "-product1.jpg)";
        } 
        elseif ($file_size > 2 * 1024 * 1024) {
            $error_msg = "Error: Image size is too large! (Max 2MB)";
        } 
        else {
            // Upload to the existing directory
            $target_dir = "../img/$image_prefix/";
            if (move_uploaded_file($file_tmp, $target_dir . $image)) {
                $target_image = $image;
            } else {
                $error_msg = "Error: Target folder '../img/$image_prefix/' does not exist or is not writable!";
            }
        }
    }

    // Update only if there are no errors
    if (empty($error_msg)) {
        $update_stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image=?, category_id=?, stock=? WHERE id=?");
        $update_stmt->execute([$name, $description, $price, $target_image, $category_id, $stock, $id]);
        $success_msg = "Product Updated Successfully!";
        
        // Refresh displayed data
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

require_once "sidebar.php";
?>

<main class="edit-product-page">
    <div class="page-header">
        <h2>Edit Product: <span
                style="color: var(--color-primary);"><?php echo htmlspecialchars($product['name']); ?></span></h2>
        <a href="products.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to List</a>
    </div>

    <div class="form-container">
        <?php if (!empty($error_msg)): ?>
        <div
            style="background: #f8d7da; color: #842029; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border-left: 5px solid #dc3545;">
            <i class="fas fa-exclamation-triangle"></i> <?php echo $error_msg; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($success_msg)): ?>
        <div
            style="background: #d1e7dd; color: #0f5132; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; border-left: 5px solid #198754;">
            <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
        </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="input-group">
                    <label>Product Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                <div class="input-group">
                    <label>Price ($)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="input-group">
                    <label>Category</label>
                    <select name="category" required>
                        <?php foreach ($all_categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"
                            <?php echo ($cat['id'] == $product['category_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>
                </div>
            </div>

            <div class="input-group">
                <label>Description</label>
                <textarea name="description"
                    rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <div class="form-row align-center" style="margin-top:20px;">
                <div class="input-group" style="flex: 2;">
                    <label>Change Photo (Must start with category name, e.g., dresses-xxx.jpg)</label>
                    <input type="file" name="image" accept="image/*" class="file-input">
                </div>
                <div class="current-img-preview" style="flex: 1; text-align: center;">
                    <label>Current Photo</label>
                    <?php 
                        $imageName = $product['image']; 
                        $folder = explode('-', $imageName)[0];
                        $imagePath = "../img/$folder/$imageName";
                    ?>
                    <img id="preview" src="<?php echo $imagePath; ?>"
                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 15px; display: block; margin: 10px auto; border: 3px solid var(--color-primary);">
                </div>
            </div>

            <button type="submit" name="update" class="submit-btn">
                <i class="fas fa-save"></i> Update Product Details
            </button>
        </form>
    </div>
</main>

<script>
const imageInput = document.querySelector('input[type="file"]');
const previewImg = document.getElementById('preview');
imageInput.onchange = evt => {
    const [file] = imageInput.files;
    if (file) {
        previewImg.src = URL.createObjectURL(file);
    }
}
</script>