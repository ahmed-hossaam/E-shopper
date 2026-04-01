<?php
session_start();

if (!isset($_SESSION['last_order_id'])) {
    header("Location: index.php");
    exit;
}

require_once "./includes/header.php";
?>

<div class="container-fluid pt-5">
    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="display-1 text-primary mb-4"><i class="fa fa-check-circle"></i></div>
            <h1 class="display-4 font-weight-semi-bold mb-3">Thank You,
                <?= htmlspecialchars($_SESSION['last_customer']) ?>!</h1>
            <p class="lead mb-4">Your order has been placed successfully. We are getting it ready for you!</p>

            <div class="card border-secondary mb-5">
                <div class="card-header bg-secondary border-0">
                    <h4 class="font-weight-semi-bold m-0">Order Summary</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h6 class="font-weight-medium">Order Number:</h6>
                        <h6 class="font-weight-medium">#<?= $_SESSION['last_order_id'] ?></h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6 class="font-weight-medium">Total Amount Paid:</h6>
                        <h6 class="font-weight-medium text-primary">$<?= number_format($_SESSION['last_total'], 2) ?>
                        </h6>
                    </div>
                </div>
                <div class="card-footer border-secondary bg-transparent">
                    <p class="small text-muted">A confirmation email will be sent to you shortly.</p>
                </div>
            </div>

            <a href="index.php" class="btn btn-primary py-3 px-5">Continue Shopping</a>
        </div>
    </div>
</div>

<?php 
unset($_SESSION['last_customer']);
unset($_SESSION['last_total']);
unset($_SESSION['last_order_id']);

require_once "./includes/footer.php"; 
?>