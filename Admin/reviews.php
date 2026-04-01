<style>
/* Makes the table fill the entire card container */
.table {
    width: 100% !important;
    border-collapse: collapse;
    margin: 1rem 0;
}

/* Distribution of spacing between columns and vertical alignment */
.table th,
.table td {
    padding: 1.2rem 1rem;
    text-align: left;
    /* Ensures text starts from the left consistently */
    vertical-align: middle;
}

/* Increases spacing for User and Email columns to prevent crowding */
.table td:nth-child(2),
.table td:nth-child(3) {
    min-width: 150px;
}

/* Aligns the action buttons or specific text to the center */
.text-center {
    text-align: center !important;
}

/* Hover effect for table rows to improve readability */
.table tbody tr:hover {
    background-color: #f8faff;
}

/* --- Status Badges Styling --- */
.status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-block;
    text-align: center;
    min-width: 90px;
}

/* Pending Status - Yellow/Gold theme */
.status.pending {
    background-color: #fff9db;
    color: #f08c00;
    border: 1px solid #ffe066;
}

/* Approved Status - Green theme */
.status.approved {
    background-color: #ebfbee;
    color: #2f9e44;
    border: 1px solid #b2f2bb;
}

/* Rejected Status - Red theme */
.status.rejected {
    background-color: #fff5f5;
    color: #e03131;
    border: 1px solid #ffc9c9;
}
</style>

<?php
session_start();
require '../includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Logic for Approving, Rejecting, or Deleting reviews
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];

    if ($action == 'approve') {
        $conn->prepare("UPDATE reviews SET status = 1 WHERE id = ?")->execute([$id]);
    } elseif ($action == 'reject') {
        $conn->prepare("UPDATE reviews SET status = 2 WHERE id = ?")->execute([$id]);
    } elseif ($action == 'delete') {
        $conn->prepare("DELETE FROM reviews WHERE id = ?")->execute([$id]);
    }
    header("Location: reviews.php");
    exit();
}

// Fetch all reviews sorted by latest
$reviews = $conn->query("SELECT * FROM reviews ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC); 

require_once "sidebar.php";
?>

<main class="orders-page">
    <div class="page-header">
        <h2>Manage Reviews</h2>
    </div>

    <div class="custom-card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>User</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($reviews as $rev): 
                        // Fetching Product Name based on product_id
                        $p_stmt = $conn->prepare("SELECT name FROM products WHERE id = ?");
                        $p_stmt->execute([$rev['product_id']]);
                        $product_name = $p_stmt->fetchColumn() ?: 'Deleted Product';

                        // Fetching User Name (using user_id column)
                        $u_stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
                        $u_stmt->execute([$rev['id']]); 
                        $user_name = $u_stmt->fetchColumn() ?: 'Guest';
                    ?>
                    <tr>
                        <td style="font-weight: 600; color: #333;"><?= htmlspecialchars($product_name) ?></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div
                                    style="width: 30px; height: 30px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;">
                                    <?= strtoupper(substr($user_name, 0, 1)) ?>
                                </div>
                                <?= htmlspecialchars($user_name) ?>
                            </div>
                        </td>
                        <td style="max-width: 250px; color: #666; font-size: 0.9rem; line-height: 1.4;">
                            <?= htmlspecialchars($rev['review_text']) ?>
                        </td>
                        <td>
                            <?php if($rev['status'] == 0): ?>
                            <span class="status pending">Pending</span>

                            <?php elseif($rev['status'] == 1): ?>
                            <span class="status approved">Approved</span>

                            <?php else: ?>
                            <span class="status rejected">Rejected</span>

                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <?php if($rev['status'] != 1): ?>
                                <a href="reviews.php?action=approve&id=<?= $rev['id'] ?>"
                                    style="background: #22c55e; color: white; padding: 6px 10px; border-radius: 6px;"
                                    title="Approve">
                                    <i class="fas fa-check"></i>
                                </a>
                                <?php endif; ?>

                                <?php if($rev['status'] != 2): ?>
                                <a href="reviews.php?action=reject&id=<?= $rev['id'] ?>"
                                    style="background: #fd7e14; color: white; padding: 6px 10px; border-radius: 6px;"
                                    title="Reject">
                                    <i class="fas fa-times"></i>
                                </a>
                                <?php endif; ?>

                                <a href="reviews.php?action=delete&id=<?= $rev['id'] ?>"
                                    style="background: #ff7782; color: white; padding: 6px 10px; border-radius: 6px;"
                                    onclick="return confirm('Delete permanently?')" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>