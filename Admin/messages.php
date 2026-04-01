<style>
/* --- Messages Page Custom Styles --- */

/* Ensure the table fills the card container */
.messages-table {
    width: 100% !important;
    border-collapse: collapse;
    margin: 1rem 0;
}

/* Cell padding and vertical alignment */
.messages-table th,
.messages-table td {
    padding: 1.2rem 1rem;
    text-align: left;
    vertical-align: middle;
    border-bottom: 1px solid #f0f2f5;
}

/* Style for the envelope icon box */
.msg-icon-box {
    width: 35px;
    height: 35px;
    background: #eef2ff;
    color: #6366f1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 0.9rem;
}

/* Sender name styling */
.sender-name {
    font-weight: 600;
    color: #1e293b;
}

/* Style for the message snippet (preview text) */
.msg-text {
    color: #64748b;
    font-size: 0.85rem;
    max-width: 300px;
}

/* Date badge styling */
.date-tag {
    background: #f1f5f9;
    color: #475569;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Hover effect on rows */
.messages-table tbody tr:hover {
    background-color: #f8faff;
}

/* Center alignment helper */
.text-center {
    text-align: center !important;
}
</style>

<?php
session_start();
require '../includes/db.php';

// Check Admin Authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle Delete Request
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $del_stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $del_stmt->execute([$id]);
    header("Location: messages.php");
    exit();
}

// Fetch all messages from database
$query = "SELECT * FROM contact_messages ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "sidebar.php";
?>

<main class="orders-page">
    <div class="page-header">
        <h2>Messages Inbox</h2>
    </div>

    <div class="custom-card">
        <div class="table-responsive">
            <table class="messages-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Icon</th>
                        <th>Sender</th>
                        <th>Subject</th>
                        <th>Message Snippet</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($messages as $msg): ?>
                    <tr>
                        <td>
                            <div class="msg-icon-box">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </td>
                        <td class="sender-name"><?php echo htmlspecialchars($msg['name']); ?></td>
                        <td style="font-weight: 500; color: #334155;"><?php echo htmlspecialchars($msg['subject']); ?>
                        </td>
                        <td class="msg-text">
                            <?php echo htmlspecialchars(substr($msg['message'], 0, 50)) . '...'; ?>
                        </td>
                        <td>
                            <span class="date-tag">
                                <i class="far fa-calendar-alt" style="margin-right: 5px;"></i>
                                <?php echo date('d M Y', strtotime($msg['created_at'])); ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="messages.php?delete=<?php echo $msg['id']; ?>"
                                style="background: #ff7782; color: white; padding: 7px 10px; border-radius: 8px; transition: 0.3s;"
                                onclick="return confirm('Are you sure you want to delete this message?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>