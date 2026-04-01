<?php
session_start();
header('Content-Type: application/json');

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    $shipping = 10.00; // Fixed shipping

    if (isset($_SESSION['cart'][$id])) {
        if ($action === 'plus') $_SESSION['cart'][$id]['qty']++;
        if ($action === 'minus' && $_SESSION['cart'][$id]['qty'] > 1) $_SESSION['cart'][$id]['qty']--;
        if ($action === 'delete') unset($_SESSION['cart'][$id]);
    }

    // Calculate All Totals
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['price'] * $item['qty'];
    }
    
    $item_total = isset($_SESSION['cart'][$id]) ? ($_SESSION['cart'][$id]['price'] * $_SESSION['cart'][$id]['qty']) : 0;
    $grand_total = $subtotal + $shipping;

    echo json_encode([
        'status'     => 'success',
        'newQty'     => $_SESSION['cart'][$id]['qty'] ?? 0,
        'itemTotal'  => number_format($item_total, 2),
        'subtotal'   => number_format($subtotal, 2),
        'grandTotal' => number_format($grand_total, 2),
        'cartCount'  => count($_SESSION['cart'])
    ]);
    exit;
}