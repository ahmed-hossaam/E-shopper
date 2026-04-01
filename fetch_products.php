<?php
// 1. Session start is necessary for favorites
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Database connection
require_once "./includes/db.php"; 

// 3. Set content type as JSON
header('Content-Type: application/json');

try {
    // Pagination settings
    $limit = 12;
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($page - 1) * $limit;
    
    // Receive filters from URL
    $id          = $_GET['id'] ?? 'all';
    $price_range = $_GET['price_range'] ?? 'all';
    $search       = $_GET['search'] ?? '';
    $sort        = $_GET['sort'] ?? 'latest';

    // Build base query
    $sql = "SELECT * FROM products WHERE 1=1";
    $params = [];

    // Category filter
    if ($id && $id !== 'all') {
        $sql .= " AND category_id = ?";
        $params[] = $id;
    }

    // Price filter
    if ($price_range !== 'all' && strpos($price_range, '-') !== false) {
        // Splits '100-200' into array [100, 200]
        $range = explode('-', $price_range);
        $sql .= " AND price BETWEEN ? AND ?";
        $params[] = (float)$range[0]; // Minimum price
        $params[] = (float)$range[1]; // Maximum price
    }

    // Search filter
    if (!empty($search)) {
        $sql .= " AND name LIKE ?";
        $params[] = "%$search%";
    }

    // --- Sorting logic ---
    $orderBy = " ORDER BY id DESC"; // Default: Latest
    if ($sort === 'price-asc') {
        $orderBy = " ORDER BY price ASC";
    } elseif ($sort === 'price-desc') {
        $orderBy = " ORDER BY price DESC";
    }

    // 1. Calculate total products (without LIMIT) for pagination
    $stmt_count = $conn->prepare($sql);
    $stmt_count->execute($params);
    $total_products = $stmt_count->rowCount(); 
    $total_pages = ceil($total_products / $limit);

    // 2. Fetch actual products with sorting and LIMIT/OFFSET
    $final_sql = $sql . $orderBy . " LIMIT $limit OFFSET $offset";
    $stmt = $conn->prepare($final_sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Current user's favorites list
    $fav_list = $_SESSION['favorites'] ?? [];

    // Send final result
    echo json_encode([
        'status'         => 'success',
        'products'       => $products,
        'total_pages'    => (int)$total_pages,
        'total_products' => (int)$total_products,
        'current_page'   => (int)$page,
        'fav_list'       => array_values($fav_list) // Ensure it returns a simple array
    ]);

} catch (Exception $e) {
    // Error handling
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}