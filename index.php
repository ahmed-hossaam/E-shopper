<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require "./includes/db.php";  

// 1. Fetch all categories for the sidebar
$stmt = $conn->prepare("SELECT id, name FROM `categories` ORDER BY name ASC");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. Fetch 8 random products for "Trendy Products" section
$stmt_rand = $conn->prepare("SELECT * FROM products ORDER BY RAND() LIMIT 8");
$stmt_rand->execute();
$random_products = $stmt_rand->fetchAll(PDO::FETCH_ASSOC);

// 3. Fetch 8 latest products for "Just Arrived" section
$stmt_new = $conn->prepare("SELECT * FROM products ORDER BY id DESC LIMIT 8");
$stmt_new->execute();
$new_products = $stmt_new->fetchAll(PDO::FETCH_ASSOC);

include "./includes/header.php"; 
?>

<div class="container-fluid mb-5 mt-4">
    <div class="row px-xl-5">
        <div class="col-lg-3 d-none d-lg-block">
            <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100"
                data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                <h6 class="m-0 text-white">Categories</h6>
                <i class="fa fa-angle-down text-white"></i>
            </a>
            <nav class="collapse show navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0"
                id="navbar-vertical">
                <div class="navbar-nav w-100 overflow-hidden cat-list-container" style="height: 410px">
                    <?php foreach ($categories as $cat): ?>
                    <a href="shop.php?id=<?= (int)$cat['id'] ?>"
                        class="nav-item nav-link nav-cat"><?= htmlspecialchars($cat['name']) ?></a>
                    <?php endforeach; ?>
                </div>
            </nav>
        </div>

        <div class="col-lg-9">
            <div class="hero-header shadow-sm"
                style="background-image: url('https://images.unsplash.com/photo-1441986300917-64674bd600d8?q=80&w=2070&auto=format&fit=crop'); height: 475px;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8 text-white pl-5">
                            <div class="glass-card p-5" data-aos="fade-right">
                                <h4 class="text-primary text-uppercase font-weight-medium mb-3">10% Off Your First Order
                                </h4>
                                <h1 class="display-4 font-weight-bold mb-4 text-dark">Fashionable <br> Collection</h1>
                                <a href="shop.php" class="btn btn-primary py-3 px-5 rounded-pill shadow">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid pt-5">
    <div class="row px-xl-5 pb-3">
        <div class="col-lg-3 col-md-6 col-sm-12 pb-3">
            <div class="feature-card shadow-sm mb-4">
                <i class="fa fa-check text-primary"></i>
                <h5 class="font-weight-semi-bold m-0">Quality Product</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-3">
            <div class="feature-card shadow-sm mb-4">
                <i class="fa fa-shipping-fast text-primary"></i>
                <h5 class="font-weight-semi-bold m-0">Free Shipping</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-3">
            <div class="feature-card shadow-sm mb-4">
                <i class="fas fa-exchange-alt text-primary"></i>
                <h5 class="font-weight-semi-bold m-0">14-Day Return</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-3">
            <div class="feature-card shadow-sm mb-4">
                <i class="fa fa-phone-volume text-primary"></i>
                <h5 class="font-weight-semi-bold m-0">24/7 Support</h5>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Trendy Products</span></h2>
    </div>
    <div class="row px-xl-5 pb-3">
        <?php foreach ($random_products as $product): 
            $imageName = $product['image']; 
            $folder = explode('-', $imageName)[0];
            $imagePath = "img/$folder/$imageName";
        ?>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1 mb-3">
            <div class="card product-item border-0 mb-4">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <img class="img-fluid w-100" src="<?= htmlspecialchars($imagePath) ?>"
                        alt="<?= htmlspecialchars($product['name']) ?>">
                </div>
                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                    <h6 class="text-truncate mb-3"><?= htmlspecialchars($product['name']) ?></h6>
                    <div class="d-flex justify-content-center">
                        <h6 class="text-primary font-weight-bold">$<?= number_format($product['price'], 2) ?></h6>
                        <h6 class="text-muted ml-2"><del>$<?= number_format($product['price'] * 1.5, 2) ?></del></h6>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between bg-light border">
                    <a href="detail.php?id=<?= (int)$product['id'] ?>" class="btn btn-sm text-dark p-0">
                        <i class="fas fa-eye text-primary mr-1"></i>View
                    </a>
                    <?php $is_fav = isset($_SESSION['favorites']) && in_array($product['id'], $_SESSION['favorites']); ?>
                    <a href="javascript:void(0)" class="add-to-fav btn btn-sm p-0" data-id="<?= $product['id'] ?>"
                        data-url="add_to_fav.php">
                        <i class="fas fa-heart <?= $is_fav ? 'text-primary' : 'text-dark' ?>"></i>
                    </a>
                    <a href="javascript:void(0)" class="add-to-cart btn btn-sm text-dark p-0"
                        data-id="<?= $product['id'] ?>" data-url="add_to_cart.php">
                        <i class="fas fa-shopping-cart"></i> Add
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="container-fluid bg-secondary my-5">
    <div class="row justify-content-md-center py-5 px-xl-5">
        <div class="col-md-6 col-12 py-5 text-center">
            <h2 class="section-title px-5 mb-3"><span class="bg-secondary px-2">Stay Updated</span></h2>
            <p>Join our newsletter and receive updates on latest arrivals and offers.</p>
            <form action="subscribe.php" method="POST">
                <div class="input-group">
                    <input type="email" name="sub_email" class="form-control border-white p-4"
                        placeholder="Email Goes Here" required>
                    <div class="input-group-append">
                        <button class="btn btn-primary px-4">Subscribe</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Just Arrived</span></h2>
    </div>
    <div class="row px-xl-5 pb-3">
        <?php foreach ($new_products as $product): 
            $imageName = $product['image']; 
            $folder = explode('-', $imageName)[0];
            $imagePath = "img/$folder/$imageName";
        ?>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1 mb-3">
            <div class="card product-item border-0 mb-4">
                <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                    <img class="img-fluid w-100" src="<?= htmlspecialchars($imagePath) ?>"
                        alt="<?= htmlspecialchars($product['name']) ?>">
                </div>
                <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                    <h6 class="text-truncate mb-3"><?= htmlspecialchars($product['name']) ?></h6>
                    <div class="d-flex justify-content-center">
                        <h6 class="text-primary font-weight-bold">$<?= number_format($product['price'], 2) ?></h6>
                        <h6 class="text-muted ml-2"><del>$<?= number_format($product['price'] * 1.5, 2) ?></del></h6>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between bg-light border">
                    <a href="detail.php?id=<?= (int)$product['id'] ?>" class="btn btn-sm text-dark p-0">
                        <i class="fas fa-eye text-primary mr-1"></i>View
                    </a>
                    <?php $is_fav = isset($_SESSION['favorites']) && in_array($product['id'], $_SESSION['favorites']); ?>
                    <a href="javascript:void(0)" class="add-to-fav btn btn-sm p-0" data-id="<?= $product['id'] ?>"
                        data-url="add_to_fav.php">
                        <i class="fas fa-heart <?= $is_fav ? 'text-primary' : 'text-dark' ?>"></i>
                    </a>
                    <a href="javascript:void(0)" class="add-to-cart btn btn-sm text-dark p-0"
                        data-id="<?= $product['id'] ?>" data-url="add_to_cart.php">
                        <i class="fas fa-shopping-cart"></i> Add
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once "./includes/footer.php"; ?>