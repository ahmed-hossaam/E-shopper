<?php
session_start();
require '../includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch categories from the database
$cat_stmt = $conn->prepare("SELECT * FROM categories ORDER BY name ASC");
$cat_stmt->execute();
$all_categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

$error_msg = ""; 
$success_msg = "";

if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category'];

    // Fetch selected category name for validation
    $check_cat = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $check_cat->execute([$category_id]);
    $selected_category_name = strtolower($check_cat->fetchColumn());

    $image = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_size = $_FILES['image']['size'];

    // 1. Extract prefix from image name (first part before "-")
    $image_parts = explode('-', $image);
    $image_prefix = strtolower($image_parts[0]);

    // --- Validation Logic ---
    if ($image_prefix !== $selected_category_name) {
        $error_msg = "Error: Image name must start with '" . $selected_category_name . "-' (Ex: " . $selected_category_name . "-item1.jpg)";
    } 
    elseif ($file_size > 2 * 1024 * 1024) {
        $error_msg = "Error: Image size is too large! (Max 2MB)";
    } 
    else {
        // Path based on the prefix
        $target_dir = "../img/$image_prefix/";
        
        if (move_uploaded_file($file_tmp, $target_dir . $image)) {
            // Execute insert only if image was uploaded successfully
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, category_id, stock) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $image, $category_id, $stock]);
            $success_msg = "Product Added Successfully!";
        } else {
            $error_msg = "Error: Folder '../img/$image_prefix/' not found or not writable!";
        }
    }
}

require_once "sidebar.php"; // Include sidebar
?>

<main class="add-product-page">
    <div class="page-header">
        <h2>Add New Product</h2>
        <a href="products.php" class="back-btn" style="padding:10px 20px ;color:white;border-radius:10px"><i
                class="fas fa-arrow-left"></i> Back to List</a>
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
                    <input type="text" name="name" placeholder="Enter product name" required>
                </div>
                <div class="input-group">
                    <label>Price ($)</label>
                    <input type="number" step="0.01" name="price" placeholder="0.00" required>
                </div>
            </div>

            <div class="form-row">
                <div class="input-group">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="" disabled selected>Select Category</option>
                        <?php foreach ($all_categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="input-group">
                    <label>Initial Stock</label>
                    <input type="number" name="stock" placeholder="Quantity" required>
                </div>
            </div>

            <div class="input-group">
                <label>Description</label>
                <textarea name="description" rows="4" placeholder="Brief description..."></textarea>
            </div>

            <div class="form-row align-center" style="margin-top:20px;">
                <div class="input-group" style="flex: 2;">
                    <label>Product Image (Name must start with category prefix)</label>
                    <input type="file" name="image" id="imgInput" accept="image/*" class="file-input" required>
                </div>
                <div class="current-img-preview" style="flex: 1; text-align: center;">
                    <label>Preview</label>
                    <img id="preview" src="../img/no-image.png"
                        style="width: 100px; height: 100px; object-fit: cover; border-radius: 15px; display: block; margin: 10px auto; border: 3px dashed var(--color-info-light);">
                </div>
            </div>

            <button type="submit" name="add" class="submit-btn">
                <i class="fas fa-plus"></i> Save & Publish Product
            </button>
        </form>
    </div>
</main>

<script>
const imgInput = document.getElementById('imgInput');
const preview = document.getElementById('preview');
imgInput.onchange = evt => {
    const [file] = imgInput.files;
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.borderStyle = "solid";
        preview.style.borderColor = "var(--color-primary)";
    }
}
</script>