<?php
session_start();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    if (!isset($_SESSION['favorites'])) {
        $_SESSION['favorites'] = [];
    }

    // Check if product is already in favorites
    $key = array_search($id, $_SESSION['favorites']);
    
    if ($key !== false) {
        // Remove from favorites if exists (Toggle logic)
        unset($_SESSION['favorites'][$key]);
        // Re-index the array
        $_SESSION['favorites'] = array_values($_SESSION['favorites']); 
    } else {
        // Add to favorites if not exists
        $_SESSION['favorites'][] = $id;
    }
}

// Redirect back to previous page or index if referer is missing
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'newCount' => count($_SESSION['favorites'])
    ]);
    exit;
}


header("Location: " . $_SERVER['HTTP_REFERER']);
exit;