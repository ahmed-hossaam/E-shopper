<?php 
session_start();
require_once "./includes/db.php"; 

// Check for authentication or Guest Mode
if (!isset($_SESSION['user_id']) && !isset($_GET['mode'])) {
    header("Location: checkout_login.php");
    exit;
}

// Fetch user data if logged in to pre-fill the form
$u_name = ""; $u_email = "";
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $u = $stmt->fetch();
    if ($u) {
        $u_name = $u['name'];
        $u_email = $u['email'];
    }
}

// Ensure cart is not empty
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cart_items)) {
    header("Location: cart.php");
    exit;
}

// Calculate Order Totals
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += ($item['price'] * $item['qty']);
}

$shipping = 10;
$discount = isset($_SESSION['applied_discount']) ? $_SESSION['applied_discount'] : 0;
$total = ($subtotal + $shipping) - $discount;

require_once "./includes/header.php";
?>

<div class="container-fluid bg-secondary mb-5">
    <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
        <h1 class="font-weight-semi-bold text-uppercase mb-3">Checkout</h1>
        <div class="d-inline-flex text-dark">
            <p class="m-0"><a href="index.php">Home</a></p>
            <p class="m-0 px-2">-</p>
            <p class="m-0">Checkout</p>
        </div>
    </div>
</div>
<div class="container-fluid pt-5">
    <form action="place_order.php" method="POST">
        <div class="row px-xl-5">
            <div class="col-lg-8">
                <div class="card border-secondary mb-5 shadow-sm">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0 text-dark">Billing Address</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="font-weight-medium">First Name</label>
                                <input class="form-control py-4" type="text" name="first_name" placeholder="John"
                                    value="<?= htmlspecialchars($u_name) ?>" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="font-weight-medium">Last Name</label>
                                <input class="form-control py-4" type="text" name="last_name" placeholder="Doe"
                                    required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="font-weight-medium">E-mail</label>
                                <input class="form-control py-4" type="email" name="email"
                                    placeholder="example@email.com" value="<?= htmlspecialchars($u_email) ?>" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="font-weight-medium">Mobile No</label>
                                <input class="form-control py-4" type="text" name="mobile" placeholder="+20 123 456 789"
                                    required>
                            </div>
                            <div class="col-md-12 form-group">
                                <label class="font-weight-medium">Detailed Address</label>
                                <input class="form-control py-4" type="text" name="address1"
                                    placeholder="123 Street, Building, Apartment" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="font-weight-medium">Country</label>
                                <select class="custom-select" style="height: calc(1.5em + 1.5rem + 2px);"
                                    name="country">
                                    <option selected>Egypt</option>
                                    <option>United States</option>
                                    <option>Algeria</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="font-weight-medium">City</label>
                                <input class="form-control py-4" type="text" name="city" placeholder="Cairo" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-secondary mb-5 shadow-sm">
                    <div class="card-header bg-secondary border-0">
                        <h4 class="font-weight-semi-bold m-0 text-dark">Order Summary</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="font-weight-bold mb-4 pb-2">Products</h5>
                        <div class="product-list mb-4">
                            <?php foreach($cart_items as $item): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <p class="mb-0 text-muted"><?= htmlspecialchars($item['name']) ?> <span
                                        class="badge badge-secondary ml-1">x<?= $item['qty'] ?></span></p>
                                <p class="mb-0 font-weight-medium">
                                    $<?= number_format($item['price'] * $item['qty'], 2) ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="summary-details pt-3 border-top">
                            <div class="d-flex justify-content-between mb-3">
                                <h6 class="font-weight-medium">Subtotal</h6>
                                <h6 class="font-weight-medium">$<?= number_format($subtotal, 2) ?></h6>
                            </div>
                            <?php if($discount > 0): ?>
                            <div class="d-flex justify-content-between mb-3">
                                <h6 class="font-weight-medium text-success">Discount</h6>
                                <h6 class="font-weight-medium text-success">-$<?= number_format($discount, 2) ?></h6>
                            </div>
                            <?php endif; ?>
                            <div class="d-flex justify-content-between mb-3">
                                <h6 class="font-weight-medium">Shipping</h6>
                                <h6 class="font-weight-medium">$<?= number_format($shipping, 2) ?></h6>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <h4 class="font-weight-bold">Total</h4>
                                <h4 class="font-weight-bold text-primary">$<?= number_format($total, 2) ?></h4>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="subtotal" value="<?= $subtotal ?>">
                    <input type="hidden" name="discount" value="<?= $discount ?>">
                    <input type="hidden" name="total_amount" value="<?= $total ?>">

                    <div class="card-footer border-secondary bg-transparent">
                        <h5 class="font-weight-bold mb-4">Payment Method</h5>
                        <div class="form-group">
                            <div class="custom-control custom-radio mb-3">
                                <input type="radio" class="custom-control-input" name="payment_method" value="paypal"
                                    id="paypal" checked>
                                <label class="custom-control-label" for="paypal">Paypal </label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" name="payment_method" value="cash"
                                    id="cash">
                                <label class="custom-control-label" for="cash">Cash on Delivery </label>
                            </div>
                        </div>
                        <button type="submit" name="place_order_btn"
                            class="btn btn-lg btn-block btn-primary font-weight-bold my-4 py-3 shadow">
                            Place Order Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php require_once "./includes/footer.php"; ?>