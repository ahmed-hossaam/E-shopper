<?php
session_start();
require_once "./includes/db.php";

// Auth check: Redirect if not logged in
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

$user_id = $_SESSION['user_id'];
$msg = "";

// 1. Fetch current user data
$stmt = $conn->prepare("SELECT name, email, phone, address, password FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// 2. Fallback: Get latest info from orders if profile is incomplete
if (empty($user['phone']) || empty($user['address'])) {
    $extraStmt = $conn->prepare("SELECT mobile, address FROM orders WHERE email = ? ORDER BY created_at DESC LIMIT 1");
    $extraStmt->execute([$user['email']]);
    $extra = $extraStmt->fetch();
    
    if (empty($user['phone'])) $user['phone'] = $extra['mobile'] ?? '';
    if (empty($user['address'])) $user['address'] = $extra['address'] ?? '';
}

// 3. Handle Profile Update Request
if (isset($_POST['update_all'])) {
    // Sanitize basic inputs
    $name    = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $email   = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone   = htmlspecialchars(strip_tags(trim($_POST['phone'])));
    $address = htmlspecialchars(strip_tags(trim($_POST['address'])));
    $old_email = $user['email'];
    
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $hashed_pass = $user['password']; 

    // Validate email uniqueness if changed
    if ($email !== $old_email) {
        $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $checkEmail->execute([$email, $user_id]);
        if ($checkEmail->rowCount() > 0) {
            $msg = "Error: This email is already taken by another account.";
        }
    }

    // Process update if no errors
    if (empty($msg)) {
        // Handle password change logic
        if (!empty($new_pass)) {
            if ($new_pass === $confirm_pass) {
                $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
            } else {
                $msg = "Passwords do not match!";
            }
        }

        if (empty($msg)) {
            try {
                $conn->beginTransaction();
                
                // Update User Table
                $update = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, address=?, password=? WHERE id=?");
                $update->execute([$name, $email, $phone, $address, $hashed_pass, $user_id]);

                // Sync orders with new email if changed to keep history linked
                if ($email !== $old_email) {
                    $upOrders = $conn->prepare("UPDATE orders SET email = ? WHERE email = ?");
                    $upOrders->execute([$email, $old_email]);
                }

                $conn->commit();

                // Update Session Data
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email; 

                header("Location: profile.php?success=1");
                exit;
            } catch (Exception $e) {
                $conn->rollBack();
                $msg = "System Error: Could not update profile.";
            }
        }
    }
}

include "./includes/header.php";
?>
<style>
@media (max-width: 500px) {

    /* نخلي الأكشنز تحت بعض ومسنترين */
    .profile-actions {
        text-align: center !important;
    }

    /* الزرار ياخد العرض اللي يكفي الكلام بتاعه وميتمطش بالعرض بزيادة */
    .save-btn {
        width: 100% !important;
        /* يفرش بالعرض عشان يبقى سهل في اللمس */
        max-width: 280px;
        /* نلم العرض شوية عشان ميبقاش ضخم بزيادة */
        white-space: nowrap;
        /* يمنع الكلام إنه ينزل سطر جديد */
        font-size: 0.9rem !important;
    }

    /* اللينك بتاع الرجوع */
    .back-to-profile-link {
        display: block;
        margin-top: 15px;
        width: 100% !important;
    }
}
</style>
<div class="container-fluid pt-5">
    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-10">
            <?php if(!empty($msg)): ?>
            <div class="alert alert-danger shadow-sm border-0 mb-4"><?= $msg ?></div>
            <?php endif; ?>

            <div class="card border-secondary mb-5 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-secondary border-0 py-3">
                    <h4 class="font-weight-semi-bold m-0 text-dark">
                        <i class="fa fa-user-edit mr-2 text-primary"></i> Edit Profile Settings
                    </h4>
                </div>
                <div class="card-body bg-white">
                    <form method="POST" id="editProfileForm">

                        <div class="row align-items-center justify-content-center mb-2 pb-3">
                            <div class="col-md-6 form-group mb-0">
                                <label class="font-weight-bold small">Full Name</label>
                                <input class="form-control" type="text" name="name"
                                    value="<?= htmlspecialchars($user['name']) ?>" required>
                            </div>
                            <div class="col-md-6 form-group mb-0">
                                <label class="font-weight-bold small">Email Address</label>
                                <input class="form-control" type="email" name="email" id="userEmail"
                                    value="<?= htmlspecialchars($user['email']) ?>" onkeyup="warnEmailChange()"
                                    required>
                                <small id="emailWarning" class="text-danger font-weight-bold mt-1"
                                    style="display:none; font-size: 11px;">
                                    <i class="fa fa-exclamation-triangle"></i> Caution: Orders will be re-linked to this
                                    new email!
                                </small>
                            </div>
                        </div>

                        <div class="row align-items-center mb-2 pb-3">
                            <div class="col-md-6 form-group mb-0">
                                <label class="font-weight-bold small">Mobile Number</label>
                                <input class="form-control" type="text" name="phone"
                                    value="<?= htmlspecialchars($user['phone']) ?>" placeholder="01xxxxxxxxx">
                            </div>
                            <div class="col-md-6 form-group mb-0">
                                <label class="font-weight-bold small">Detailed Address</label>
                                <input class="form-control" type="text" name="address"
                                    value="<?= htmlspecialchars($user['address']) ?>"
                                    placeholder="Street, City, Building">
                            </div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-md-6 form-group mb-0">
                                <label class="font-weight-bold small">New Password</label>
                                <input class="form-control" type="password" name="new_password"
                                    placeholder="Leave blank to keep current">
                            </div>
                            <div class="col-md-6 form-group mb-0">
                                <label class="font-weight-bold small">Confirm Password</label>
                                <input class="form-control" type="password" name="confirm_password"
                                    placeholder="Confirm new password">
                            </div>
                        </div>

                        <div class="row mt-4 px-3">
                            <div
                                class="col-md-12 d-flex flex-column flex-md-row align-items-center border-top pt-4 justify-content-between profile-actions">

                                <button type="submit" name="update_all"
                                    class="btn btn-primary px-4 py-3 font-weight-bold shadow-sm rounded-pill mb-3 mb-md-0 save-btn">
                                    <i class="fa fa-save mr-2"></i> Save All Changes
                                </button>

                                <a href="profile.php"
                                    class="text-muted font-weight-bold text-decoration-none back-to-profile-link">
                                    <i class="fa fa-arrow-left mr-1"></i> Back to Profile
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Live UI warning for email changes
function warnEmailChange() {
    var currentEmail = '<?= $user['email'] ?>';
    var newEmail = document.getElementById('userEmail').value;
    var warning = document.getElementById('emailWarning');

    if (newEmail !== currentEmail && newEmail !== "") {
        warning.style.display = 'block';
    } else {
        warning.style.display = 'none';
    }
}

// Client-side validation for password matching
document.getElementById('editProfileForm').onsubmit = function() {
    var pass = document.getElementsByName('new_password')[0].value;
    var confirm = document.getElementsByName('confirm_password')[0].value;
    if (pass !== "" && pass !== confirm) {
        alert("Passwords do not match!");
        return false;
    }
    return true;
};
</script>

<?php require "./includes/footer.php"; ?>