<?php
session_start();
require '../includes/db.php';
include "sidebar.php";
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_GET['id'])) { header("Location: orders.php"); exit(); }
$order_id = $_GET['id'];

$success_msg = "";
// --- Handle Status Update ---
if (isset($_POST['update_status'])) {
    $new_status = $_POST['order_status'];
    
    $update_stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    if ($update_stmt->execute([$new_status, $order_id])) {
        $success_msg = "Order status updated successfully to: " . $new_status;
    }
}
// Fetch order data
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) { die("Order not found!"); }
// Fetch order items
$items_stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$items_stmt->execute([$order_id]);
$items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<main class="order-details-page">
    <div class="page-header">
        <h2>Order Details #<?php echo $order['id']; ?></h2>
        <a href="orders.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Orders</a>
    </div>

    <div class="details-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div class="custom-card">
            <h3 style="margin-bottom:15px;"><i class="fas fa-truck"></i> Shipping Information</h3>
            <p><b>Customer:</b> <?php echo $order['first_name'] . " " . $order['last_name']; ?></p>
            <p><b>Mobile:</b> <?php echo $order['mobile']; ?></p>
            <p><b>Email:</b> <?php echo $order['email']; ?></p>
            <p><b>Address:</b> <?php echo $order['address'] . ", " . $order['city']; ?></p>
        </div>

        <div class="custom-card">
            <h3 style="margin-bottom:15px;"><i class="fas fa-edit"></i> Update Order Status</h3>

            <?php if(!empty($success_msg)): ?>
            <div
                style="background: #d1e7dd; color: #0f5132; padding: 10px; border-radius: 8px; margin-bottom: 15px; border: 1px solid #badbcc;">
                <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
            </div>
            <?php endif; ?>

            <form method="POST" style="display: flex; flex-direction: column; gap: 5px;">
                <div class="input-group">
                    <label style="display:block; margin-bottom:5px; font-weight:600;">Current Status:</label>
                    <select name="order_status"
                        style=" padding: 12px; border-radius: 10px; background: var(--color-background); font-size: 1rem;">
                        <option value="Pending" <?php if($order['order_status'] == 'Pending') echo 'selected'; ?>>
                            Pending</option>
                        <option value="Confirmed" <?php if($order['order_status'] == 'Confirmed') echo 'selected'; ?>>
                            Confirmed</option>
                        <option value="Shipped" <?php if($order['order_status'] == 'Shipped') echo 'selected'; ?>>
                            Shipped</option>
                        <option value="Delivered" <?php if($order['order_status'] == 'Delivered') echo 'selected'; ?>>
                            Delivered</option>
                        <option value="Cancelled" <?php if($order['order_status'] == 'Cancelled') echo 'selected'; ?>>
                            Cancelled</option>
                    </select>
                </div>
                <button type="submit" name="update_status" class="submit-btn"
                    style="background: var(--color-primary); color: white; padding: 12px; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: 0.3s;">
                    Save Status Change
                </button>

            </form>
            <hr style="margin: 20px 0; border: 0; border-top: 1px solid var(--color-light);">

            <p><b>Payment Method:</b> <?php echo strtoupper($order['payment_method']); ?></p>
            <p><b>Shipping:</b> $<?php echo number_format($order['shipping'], 2); ?></p>
            <p><b>Grand Total:</b> <b class="order-price"
                    style="font-size: 1.4rem;">$<?php echo number_format($order['total_price'], 2); ?></b></p>
        </div>
    </div>

    <div class="custom-card">
        <h3 style="margin-bottom:15px;">Purchased Items</h3>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($items as $item): 
        $p_stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $p_stmt->execute([$item['product_id']]);
        $product = $p_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            $imageName = $product['image']; 
            $folder = explode('-', $imageName)[0];
            $imagePath = "../img/$folder/$imageName";
            $pName = $product['name'];
        } else {
            $imagePath = "../img/no-image.png"; 
            $pName = "Deleted Product";
        }
    ?>
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <img src="<?php echo $imagePath; ?>"
                                style="width:45px; height:45px; border-radius:5px; object-fit:cover;">
                            <span><?php echo $pName; ?></span>
                        </div>
                    </td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>