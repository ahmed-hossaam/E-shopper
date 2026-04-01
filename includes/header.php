<?php

$current_page = basename($_SERVER['PHP_SELF']);
$shop_pages = ['shop.php', 'detail.php', 'cart.php', 'wishlist.php', 'checkout.php'];
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
}
if (file_exists("./includes/db.php")) {
    require_once "./includes/db.php";
} else {
    require_once "db.php";
}
$stmt = $conn->prepare("SELECT * FROM `categories`");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id']) && !empty($_GET['id']) && $current_page == 'shop.php') {
    $cat_id = $_GET['id'];
    $stmt_prod = $conn->prepare("SELECT * FROM `products` WHERE category_id = ?");
    $stmt_prod->execute([$cat_id]);
    $products = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt_prod = $conn->prepare("SELECT * FROM `products` LIMIT 12");
    $stmt_prod->execute();
    $products = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);
}
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>EShopper</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

</head>

<body>
    <!-- Topbar Start -->

    <header class="main-header sticky-top bg-white shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light py-3 px-xl-5">
            <a href="index.php" class="navbar-brand">
                <h1 class="m-0 display-5 font-weight-semi-bold">
                    <span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper
                </h1>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav mr-auto py-0">
                    <a href="index.php"
                        class="nav-item nav-link link <?= ($current_page == 'index.php') ? 'active' : '' ?>">Home</a>

                    <a href="shop.php"
                        class="nav-item nav-link link <?= (in_array($current_page, $shop_pages)) ? 'active' : '' ?>">Shop</a>
                    <a href="about.php"
                        class="nav-item nav-link link <?= ($current_page == 'about.php') ? 'active' : '' ?>">About</a>

                    <a href="contact.php"
                        class="nav-item nav-link link <?= ($current_page == 'contact.php') ? 'active' : '' ?>">Contact</a>
                </div>

                <form action="shop.php" method="GET" class="search-form mx-lg-4 my-3 my-lg-0">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search for products...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="navbar-nav ml-auto py-0 d-none d-lg-flex align-items-center">
                    <a href="favorites.php" class="btn position-relative mr-3 nav-icon-btn nav-link">
                        <i class="fas fa-heart text-primary"></i>
                        <span class="badge badge-pill badge-dark position-absolute badge-custom"
                            id="fav-count"><?= count($_SESSION['favorites'] ?? []) ?></span>
                    </a>

                    <a href="cart.php" class="btn position-relative mr-3 nav-icon-btn nav-link">
                        <i class="fas fa-shopping-cart text-primary"></i>
                        <span class="badge badge-pill badge-dark position-absolute badge-custom"
                            id="cart-count"><?= count($_SESSION['cart'] ?? []) ?></span>
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="profile.php" class="nav-item nav-link user-action-btn" title="My Profile">
                        <i class="fa fa-user-circle fa-lg text-primary"></i>
                        <span class="ml-1 d-none d-lg-inline"><?= htmlspecialchars($_SESSION['user_name']); ?></span>
                    </a>
                    <a href="logout.php" class="nav-item nav-link text-danger user-action-btn">
                        <i class="fa fa-sign-out-alt"></i>
                    </a>
                    <?php else: ?>
                    <a href="login.php"
                        class="nav-item nav-link link <?= ($current_page == 'login.php') ? 'active' : '' ?>">Login</a>
                    <a href=" signup.php"
                        class="nav-item nav-link link <?= ($current_page == 'signup.php') ? 'active' : '' ?>">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
    <!-- Navbar End -->