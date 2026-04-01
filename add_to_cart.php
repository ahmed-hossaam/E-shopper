<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "./includes/db.php";

if (isset($_GET['id'])) {
    // Cast ID to integer for security
    $product_id = (int)$_GET['id'];

    // Fetch product details from database
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Initialize cart session if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Add or update product in the session cart
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = [
                'name'  => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'qty'   => isset($_GET['qty']) ? (int)$_GET['qty'] : 1
            ];
        } else {
            // Increment quantity if product already in cart
            $requested_qty = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;
            $_SESSION['cart'][$product_id]['qty'] += $requested_qty;
        }

        // Handle AJAX request
        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'status'   => 'success',
                'newCount' => count($_SESSION['cart'])
            ]);
            exit;
        }

        // Fallback for non-AJAX requests
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}