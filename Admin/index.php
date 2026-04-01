<?php
session_start();
require '../includes/db.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
// Total Statistics
$total_users = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();

try {
    $pending_orders = $conn->query("SELECT COUNT(*) FROM orders WHERE order_status = 'pending'")->fetchColumn();
    $cancelled_orders = $conn->query("SELECT COUNT(*) FROM orders WHERE order_status = 'cancelled'")->fetchColumn();
} catch (Exception $e) {
    $pending_orders = 0;
    $cancelled_orders = 0;
}

$total_products = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_admins = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
$new_customers = $conn->query("SELECT COUNT(*) FROM users WHERE created_at >= NOW() - INTERVAL 1 DAY")->fetchColumn();

// Recent Orders
$recent_orders = $conn->query("SELECT * FROM orders ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

require_once "sidebar.php";
?>

<main class="container-content">
    <h2><i class="fas fa-chart-line"></i> Overview</h2>

    <div class="insights-1">
        <div class="sales">
            <div class="card">
                <h3>Total Users</h3>
                <div class="about">
                    <p class="total_users"><?php echo $total_users; ?></p>
                    <i class="fas fa-users" style="color: var(--color-primary);"></i>
                </div>
            </div>
        </div>
        <div class="expenses">
            <div class="card">
                <h3>Pending Orders</h3>
                <div class="about">
                    <p class="pending"><?php echo $pending_orders; ?></p>
                    <i class="fas fa-clock" style="color: #ffbb55;"></i>
                </div>
            </div>
        </div>
        <div class="income">
            <div class="card">
                <h3>Total Products</h3>
                <div class="about">
                    <p class="accepted"><?php echo $total_products; ?></p>
                    <i class="fas fa-tags" style="color: #41f1b6;"></i>
                </div>
            </div>
        </div>
    </div>

    <h2>More Analytics</h2>

    <div class="insights-1">
        <div class="sales">
            <div class="card">
                <h3>Admins</h3>
                <div class="about">
                    <p class="online_orders"><?php echo $total_admins; ?></p>
                    <i class="fas fa-user-shield" style="color: var(--color-primary);"></i>
                </div>
            </div>
        </div>
        <div class="expenses">
            <div class="card">
                <h3>Cancelled Orders</h3>
                <div class="about">
                    <p class="offline_orders"><?php echo $cancelled_orders; ?></p>
                    <i class="material-icons-sharp" style="color: #ff7782;"> cancel </i>
                </div>
            </div>
        </div>
        <div class="income">
            <div class="card">
                <h3>New Customers</h3>
                <div class="about">
                    <p class="new_customers"><?php echo $new_customers; ?></p>
                    <i class="material-icons-sharp cart" style="color: #7380ec;"> person_add </i>
                </div>
            </div>
        </div>
    </div>

    <div class="recent-orders"
        style="margin-top: 2rem; background: var(--color-white); padding: 1.5rem; border-radius: 2rem; box-shadow: var(--box-shadow);">
        <h2>Recent Orders</h2>
        <table style="width: 100%; text-align: center; border-collapse: collapse;">
            <thead>
                <tr style="height: 3rem; border-bottom: 1px solid var(--color-light);">
                    <th>Order ID</th>
                    <th>Order Status</th>
                    <th>Total Price</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($recent_orders as $order): ?>
                <tr style="height: 3.5rem; border-bottom: 1px solid var(--color-light);">
                    <td>#<?php echo $order['id']; ?></td>
                    <td class="<?php echo ($order['order_status'] == 'pending') ? 'warning' : 'primary'; ?>">
                        <?php echo $order['order_status']; ?>
                    </td>
                    <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
                    <td><a href="order_details.php?id=<?php echo $order['id']; ?>"
                            style="color: var(--color-primary);">Details</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="orders.php"
            style="display: block; text-align: center; margin-top: 1rem; color: var(--color-primary);">Show All
            Orders</a>
    </div>
</main>

<?php require_once "footer.php"; ?>