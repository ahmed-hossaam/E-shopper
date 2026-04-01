<?php
session_start();
require_once "./includes/db.php";

if (isset($_POST['place_order_btn'])) {
    
    if (empty($_SESSION['cart'])) {
        header("Location: cart.php");
        exit;
    }

    // Standardized session variable name
    $user_id = $_SESSION['user_id'] ?? null; 

    try {
        $conn->beginTransaction();

        // 1. Insert Order 
        // Note: Ensure your 'orders' table has a 'user_id' column
        $sql_order = "INSERT INTO orders (user_id, first_name, last_name, email, mobile, address, city, subtotal, discount, shipping, total_price, payment_method) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql_order);
        
        $stmt->execute([
            $user_id, // Foreign key for registered users, NULL for guests
            htmlspecialchars($_POST['first_name']),
            htmlspecialchars($_POST['last_name']),
            filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
            htmlspecialchars($_POST['mobile']),
            htmlspecialchars($_POST['address1']),
            htmlspecialchars($_POST['city']),
            $_POST['subtotal'],
            $_POST['discount'],
            10.00, // Fixed shipping rate
            $_POST['total_amount'],
            $_POST['payment_method']
        ]);

        // Get the generated auto-increment ID
        $order_id = $conn->lastInsertId();

        // 2. Insert Order Items
        $sql_items = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt_items = $conn->prepare($sql_items);

        foreach ($_SESSION['cart'] as $product_id => $item) {
            $stmt_items->execute([
                $order_id, 
                $product_id, 
                $item['qty'],
                $item['price']
            ]);
        }

        $conn->commit();

        // Save order info for the thank_you page
        $_SESSION['last_customer'] = $_POST['first_name'];
        $_SESSION['last_order_id'] = $order_id;
        $_SESSION['last_total']    = $_POST['total_amount'];

        // Clear cart and discounts
        unset($_SESSION['cart']);
        unset($_SESSION['applied_discount']);

        header("Location: thank_you.php");
        exit;

    } catch (Exception $e) {
        $conn->rollBack();
        die("Order Error: " . htmlspecialchars($e->getMessage()));
    }
}