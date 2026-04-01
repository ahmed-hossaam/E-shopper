<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$msg = "";

if (isset($_GET['delete_id'])) {
    $id_to_delete = (int)$_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    if ($stmt->execute([$id_to_delete])) {
        $msg = "Order #$id_to_delete has been deleted.";
    }
}

$query = "SELECT * FROM orders ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "sidebar.php";
?>

<main class="orders-page">
    <div class="page-header">
        <h2>Customer Orders</h2>
        <div class="stats-mini">Total Orders: <b><?php echo count($orders); ?></b></div>
    </div>

    <div class="custom-card">
        <div class="table-responsive">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Mobile</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($orders)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding:2rem;">No orders yet.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach($orders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td class="customer-name">
                            <strong><?php echo $order['first_name'] . " " . $order['last_name']; ?></strong>
                        </td>
                        <td><?php echo $order['mobile']; ?></td>
                        <td class="text-muted">
                            <?php echo date('d M Y', strtotime($order['created_at'])); ?>
                        </td>
                        <td class="order-price">
                            <strong>$<?php echo number_format($order['total_price'], 2); ?></strong>
                        </td>
                        <td>
                            <?php 
                                    $status = strtolower($order['order_status']);
                                    $status_class = 'st-pending'; // default
                                    if($status == 'completed' || $status == 'delivered') $status_class = 'st-completed';
                                    if($status == 'cancelled' || $status == 'rejected') $status_class = 'st-cancelled';
                                ?>
                            <span class="status-badge <?php echo $status_class; ?>">
                                <?php echo ucfirst($order['order_status']); ?>
                            </span>
                        </td>
                        <td class="action-btns">
                            <a href="order_details.php?id=<?php echo $order['id']; ?>" class="view-btn">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="orders.php?delete_id=<?php echo $order['id']; ?>" class="delete-btn"
                                onclick="return confirm('Delete this order permanently?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>