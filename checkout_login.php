<?php
session_start();

// 1. Redirect to checkout if already logged in (Don't confuse the user)
if (isset($_SESSION['user_id'])) {
    header("Location: checkout.php");
    exit;
}

// 2. Redirect back to cart if it's empty
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

require_once "./includes/header.php";
?>

<div class="container-fluid pt-5 pb-5 bg-light" style="min-height: 80vh;">
    <div class="text-center mb-5">
        <h2 class="font-weight-semi-bold text-uppercase border-bottom d-inline-block pb-2">How would you like to
            proceed?</h2>
        <p class="text-muted">Choose the best way to complete your purchase</p>
    </div>

    <div class="row px-xl-5 justify-content-center">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-secondary shadow-sm h-100 text-center py-4 px-3" style="border-radius: 15px;">
                <div class="card-body">
                    <div class="bg-primary-light rounded-circle mb-3 d-inline-flex align-items-center justify-content-center"
                        style="width: 80px; height: 80px; background: #fdf0ef;">
                        <i class="fa fa-user-check text-primary fa-2x"></i>
                    </div>
                    <h4 class="font-weight-semi-bold mb-3">Returning Customer</h4>
                    <p class="text-muted mb-4 small">Sign in to use your saved addresses and track your order easily.
                    </p>
                    <a href="login.php?redirect=checkout"
                        class="btn btn-primary btn-block py-2 font-weight-bold shadow-sm">Login Now</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-primary shadow h-100 text-center py-4 px-3"
                style="border-radius: 15px; border-width: 2px;">
                <div class="card-body">
                    <div class="bg-dark-light rounded-circle mb-3 d-inline-flex align-items-center justify-content-center"
                        style="width: 80px; height: 80px; background: #f1f1f1;">
                        <i class="fa fa-shopping-bag text-dark fa-2x"></i>
                    </div>
                    <h4 class="font-weight-semi-bold mb-3">Guest Checkout</h4>
                    <p class="text-muted mb-4 small">No account? No problem. You can complete your order without
                        registering.</p>
                    <a href="checkout.php?mode=guest"
                        class="btn btn-outline-dark btn-block py-2 font-weight-bold">Continue as Guest</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-secondary shadow-sm h-100 text-center py-4 px-3" style="border-radius: 15px;">
                <div class="card-body">
                    <div class="bg-secondary-light rounded-circle mb-3 d-inline-flex align-items-center justify-content-center"
                        style="width: 80px; height: 80px; background: #eef2f7;">
                        <i class="fa fa-user-plus text-info fa-2x"></i>
                    </div>
                    <h4 class="font-weight-semi-bold mb-3">New Member</h4>
                    <p class="text-muted mb-4 small">Create an account to get rewards and a faster checkout next time.
                    </p>
                    <a href="signup.php?redirect=checkout"
                        class="btn btn-outline-primary btn-block py-2 font-weight-bold">Create Account</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "./includes/footer.php"; ?>