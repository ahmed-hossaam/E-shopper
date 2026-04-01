<?php
session_start();
require_once "./includes/db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

$user_id = $_SESSION['user_id'];

// --- DELETE LOGIC ---
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Fetch user email to ensure they only delete their own orders
    $userStmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
    $userStmt->execute([$user_id]);
    $userEmail = $userStmt->fetchColumn();

    if ($userEmail) {
        // Delete the order only if it belongs to this user's email
        $delStmt = $conn->prepare("DELETE FROM orders WHERE id = ? AND email = ?");
        $delStmt->execute([$delete_id, $userEmail]);
    }

    // Refresh page to show updated list
    header("Location: profile.php");
    exit;
}
// --------------------

// 1. Fetch basic user data (Name and Email only)
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$u = $stmt->fetch();

// 2. Fetch the latest order to retrieve (Mobile, Address, City) 
// These fields are not stored in the users table
$latestOrderStmt = $conn->prepare("SELECT mobile, address, city FROM orders WHERE email = ? ORDER BY created_at DESC LIMIT 1");
$latestOrderStmt->execute([$u['email']]);
$checkoutData = $latestOrderStmt->fetch();

// 3. Fetch all orders for the history table
$orderStmt = $conn->prepare("SELECT * FROM orders WHERE email = ? ORDER BY created_at DESC");
$orderStmt->execute([$u['email']]);
$orders = $orderStmt->fetchAll();

include "./includes/header.php";
?>

<div class="container-fluid pt-5">
    <div class="row px-xl-5 mb-4">
        <div class="col-12">
            <div class="align-items-center profile-card shadow-sm border-0"
                style="border-radius: 15px; overflow: hidden;">
                <div class="profile-header text-center"
                    style="background: linear-gradient(45deg, #D19C97, #3D464D); padding: 50px 0;">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($u['name']) ?>&background=random&size=128"
                        class="profile-avatar shadow">
                    <h2 class="text-white font-weight-bold mt-3"><?= htmlspecialchars($u['name']) ?></h2>
                    <p class="badge badge-pill rgba-white-slight text-white px-3 py-2 fs-6"
                        style="background: rgba(255,255,255,0.1)">
                        <i class="fa fa-envelope mr-1"></i> <?= htmlspecialchars($u['email']) ?>
                    </p>
                </div>
                <div class="card-body bg-white py-4">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center border-right">
                            <div class="p-3">
                                <h4 class="font-weight-bold text-primary mb-0"><?= count($orders) ?></h4>
                                <span class="text-muted small text-uppercase font-weight-bold">Orders</span>
                            </div>
                        </div>

                        <div class="col-md-3 text-center border-right">
                            <div class="p-2 border rounded bg-light">
                                <h6 class="font-weight-bold mb-1 text-dark">
                                    <?= !empty($checkoutData['mobile']) ? htmlspecialchars($checkoutData['mobile']) : 'N/A' ?>
                                </h6>
                                <span class="text-muted small">Primary Mobile</span>
                            </div>
                        </div>

                        <div class="col-md-3 text-center border-right">
                            <div class="p-2 border rounded bg-light">
                                <h6 class="font-weight-bold mb-1 text-truncate" style="max-width: 180px; margin: auto;">
                                    <?= !empty($checkoutData['address']) ? htmlspecialchars($checkoutData['address']) : 'No Address' ?>
                                </h6>
                                <span class="text-muted small">Latest Address</span>
                            </div>
                        </div>

                        <div class="col-md-3 text-center">
                            <div class="d-flex flex-column px-3">
                                <a href="edit-profile.php"
                                    class="btn btn-primary btn-sm mb-2 shadow-sm font-weight-bold"
                                    style="border-radius: 20px;">
                                    <i class="fa fa-user-edit mr-1"></i> Edit Profile
                                </a>
                                <a href="logout.php" class="btn btn-outline-danger btn-sm shadow-sm font-weight-bold"
                                    style="border-radius: 20px;">
                                    <i class="fa fa-sign-out-alt mr-1"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row px-xl-5">
        <div class="col-12">
            <h4 class="font-weight-semi-bold mb-4 border-bottom pb-3">Order History</h4>

            <?php if(empty($orders)): ?>
            <div class="empty-state text-center bg-white shadow-sm border rounded p-5">
                <img src="https://cdn-icons-png.flaticon.com/512/1170/1170678.png" width="100" class="mb-4 opacity-5">
                <h4 class="font-weight-bold">No orders found for this email!</h4>
                <p class="text-muted mb-4">You haven't placed any orders yet. Explore our shop and grab something cool!
                </p>
                <a href="shop.php" class="btn btn-primary px-5 py-3 font-weight-bold shadow">Go Shopping</a>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Receiver Name</th>
                            <th>Mobile</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders as $order): ?>
                        <tr>
                            <td class="font-weight-bold text-primary">#<?= $order['id'] ?></td>
                            <td class="customer-name">
                                <strong><?= htmlspecialchars($order['first_name'] . " " . $order['last_name']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($order['mobile']) ?></td>
                            <td class="text-muted small"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                            <td class="order-price text-dark font-weight-bold">
                                $<?= number_format($order['total_price'], 2) ?>
                            </td>
                            <td class="align-middle">
                                <?php 
                                    $current_status = $order['order_status']; 
                                    switch ($current_status) {
                                        case 'Pending':   $class = 'st-pending';   break;
                                        case 'Confirmed': $class = 'st-confirmed'; break;
                                        case 'Shipped':   $class = 'st-shipped';   break;
                                        case 'Delivered': $class = 'st-delivered'; break;
                                        case 'Cancelled': $class = 'st-cancelled'; break;
                                        default:          $class = 'st-pending';   break;
                                    }
                                ?>
                                <span class="status-badge <?= $class ?>">
                                    <?= htmlspecialchars($current_status) ?>
                                </span>
                            </td>
                            <td class="action-btns text-center">
                                <a href="order_details.php?id=<?= $order['id'] ?>" class="view-btn">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="profile.php?delete_id=<?= $order['id'] ?>" class="delete-btn"
                                    onclick="return confirm('Delete order #<?= $order['id'] ?>?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include "./includes/footer.php"; ?>