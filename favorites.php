<?php
session_start();
require_once "./includes/db.php"; 
require_once "./includes/header.php";

// Fetch favorite IDs from session
$fav_ids = $_SESSION['favorites'] ?? [];
?>

<div class="container-fluid bg-secondary mb-5">
    <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
        <h1 class="font-weight-semi-bold text-uppercase mb-3">My Wishlist</h1>
        <div class="d-inline-flex">
            <p class="m-0"><a href="index.php">Home</a></p>
            <p class="m-0 px-2">-</p>
            <p class="m-0">Wishlist</p>
        </div>
    </div>
</div>

<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <?php 
        if (!empty($fav_ids)): 
            // Sanitize IDs to be integers
            $fav_ids = array_map('intval', $fav_ids);
            $placeholders = str_repeat('?,', count($fav_ids) - 1) . '?';
            
            $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
            $stmt->execute($fav_ids);
            $products = $stmt->fetchAll();

            foreach ($products as $product): 
                $imageName = $product['image']; 
                $folder = explode('-', $imageName)[0];
                $imagePath = "img/$folder/$imageName"; 
            ?>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1 mb-4">
            <div class="card product-item border-0 mb-4 shadow-sm">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <img class="img-fluid w-100" src="<?= htmlspecialchars($imagePath) ?>"
                        alt="<?= htmlspecialchars($product['name']) ?>">
                </div>
                <div class="card-body border-left border-right text-center p-0 pt-4 pb-1">
                    <h6 class="text-truncate mb-3"><?= htmlspecialchars($product['name']) ?></h6>
                    <div class="d-flex justify-content-center">
                        <h6>$<?= number_format($product['price'], 2) ?></h6>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between bg-light border">
                    <a href="detail.php?id=<?= (int)$product['id'] ?>" class="btn btn-sm text-dark p-0">
                        <i class="fas fa-eye text-primary mr-1"></i>View
                    </a>
                    <a href="javascript:void(0)" class="add-to-cart btn btn-sm text-dark p-0"
                        data-id="<?= $product['id'] ?>" data-url="add_to_cart.php">
                        <i class="fas fa-shopping-cart text-primary"></i> Add
                    </a>
                    <a href="add_to_fav.php?id=<?= (int)$product['id'] ?>" class="btn btn-sm text-dark p-0">
                        <i class="fas fa-trash text-primary mr-1"></i>Remove
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <div class="col-12 text-center py-5">
            <h3 class="font-weight-semi-bold">Your wishlist is empty!</h3>
            <p class="text-muted">Explore our shop and add some items you love.</p>
            <a href="shop.php" class="btn btn-primary mt-3 px-4 shadow-sm">Go To Shop</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once "./includes/footer.php"; ?>