<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E_COMMERCE | Admin</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container mt-4">
        <aside>
            <div class="top">
                <div class="logo">
                    <h1><span class="text-primary">E</span> SHOPPER</h1>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">close</span>
                </div>
            </div>

            <div class="sidebar">
                <a href="index.php"
                    class="<?= ($current_page == 'index.php' || $current_page == 'dashboard.php') ? 'active' : '' ?>">
                    <span class="material-icons-sharp">grid_view</span>
                    <h3>Overview</h3>
                </a>

                <a href="categories.php"
                    class="<?= ($current_page == 'categories.php' || $current_page == 'add_category.php' || $current_page == 'edit_category.php' || $current_page == 'view_category.php') ? 'active' : '' ?>">
                    <span class="material-icons-sharp">category</span>
                    <h3>Categories</h3>
                </a>

                <a href="products.php"
                    class="<?= ($current_page == 'products.php' || $current_page == 'product_details.php' || $current_page == 'add-product.php' || $current_page == 'edit_product.php') ? 'active' : '' ?>">
                    <span class="material-icons-sharp">inventory_2</span>
                    <h3>Products</h3>
                </a>

                <a href="orders.php"
                    class="<?= ($current_page == 'orders.php' || $current_page == 'order_details.php') ? 'active' : '' ?>">
                    <span class="material-icons-sharp">shopping_cart</span>
                    <h3>Orders</h3>
                </a>

                <a href="users.php" class="<?= ($current_page == 'users.php') ? 'active' : '' ?>">
                    <span class="material-icons-sharp">person_outline</span>
                    <h3>Users</h3>
                </a>

                <a href="admins.php"
                    class="<?= ($current_page == 'admins.php' || $current_page == 'add_admin.php' || $current_page == 'edit_admin.php') ? 'active' : '' ?>">
                    <span class="material-icons-sharp">admin_panel_settings</span>
                    <h3>Admins</h3>
                </a>

                <a href="reviews.php" class="<?= ($current_page == 'reviews.php') ? 'active' : '' ?>">
                    <span class="material-icons-sharp">rate_review</span>
                    <h3>Reviews</h3>
                </a>

                <a href="messages.php" class="<?= ($current_page == 'messages.php') ? 'active' : '' ?>">
                    <span class="material-icons-sharp">mail_outline</span>
                    <h3>Messages</h3>
                </a>

                <a href="logout.php">
                    <span class="material-icons-sharp">logout</span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>