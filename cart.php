<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Initialize Cart Data (The Core)
$cart_items = $_SESSION['cart'] ?? [];
$shipping = 10.00;
$subtotal = 0;

// Calculate subtotal for initial load and AJAX
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * ($item['qty'] ?? 1);
}

// 2. Handle Coupon Logic (PHP POST)
if (isset($_POST['apply_coupon'])) {
    $coupon_code = strtoupper(trim($_POST['coupon_code']));
    if ($coupon_code === "STAR") {
        $_SESSION['coupon_rate'] = 0.10; // 10% discount
        $_SESSION['coupon_msg'] = "Success! 10% discount applied.";
    } else {
        unset($_SESSION['coupon_rate']);
        $_SESSION['coupon_msg'] = "Invalid Coupon Code!";
    }
    header("Location: cart.php");
    exit();
}

// 3. Handle Quantity Updates (AJAX Gateway)
if (isset($_GET['action']) && isset($_GET['id'])) {
    header('Content-Type: application/json');
    $id = $_GET['id'];
    $act = $_GET['action'];

    if (isset($_SESSION['cart'][$id])) {
        if ($act === 'plus') $_SESSION['cart'][$id]['qty']++;
        if ($act === 'minus' && $_SESSION['cart'][$id]['qty'] > 1) $_SESSION['cart'][$id]['qty']--;
        if ($act === 'delete') unset($_SESSION['cart'][$id]);
    }

    // Recalculate totals for JSON response
    $new_subtotal = 0;
    foreach (($_SESSION['cart'] ?? []) as $item) {
        $new_subtotal += $item['price'] * $item['qty'];
    }
    $c_rate = $_SESSION['coupon_rate'] ?? 0;
    $disc = $new_subtotal * $c_rate;
    $grand = ($new_subtotal + $shipping) - $disc;
    $row_total = isset($_SESSION['cart'][$id]) ? ($_SESSION['cart'][$id]['price'] * $_SESSION['cart'][$id]['qty']) : 0;

    echo json_encode([
        'status' => 'success',
        'newQty' => $_SESSION['cart'][$id]['qty'] ?? 0,
        'itemTotal' => number_format($row_total, 2),
        'subtotal' => number_format($new_subtotal, 2),
        'discount' => number_format($disc, 2),
        'grandTotal' => number_format($grand, 2),
        'cartCount' => count($_SESSION['cart'] ?? [])
    ]);
    exit();
}

// 4. Final Calculations for Page Display
$coupon_rate = $_SESSION['coupon_rate'] ?? 0;
$discount_amount = $subtotal * $coupon_rate;
$total = ($subtotal + $shipping) - $discount_amount;
$coupon_msg = $_SESSION['coupon_msg'] ?? "";
unset($_SESSION['coupon_msg']);

// Include Header after all logic is done
require_once "./includes/header.php";
?>
<div class="container-fluid bg-secondary mb-5">
    <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
        <h1 class="font-weight-semi-bold text-uppercase mb-3">Shopping Cart</h1>
        <div class="d-inline-flex">
            <p class="m-0"><a href="index.php">Home</a></p>
            <p class="m-0 px-2">-</p>
            <p class="m-0">Shopping Cart</p>
        </div>
    </div>
</div>

<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-lg-8 table-responsive mb-5">
            <table class="table text-center mb-0 cart-table shadow-sm">
                <thead class="bg-secondary text-dark">
                    <tr>
                        <th>Products</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody class="align-middle">
                    <?php if (!empty($cart_items)): ?>
                    <?php foreach ($cart_items as $id => $item): 
                    $total_item_price = $item['price'] * ($item['qty'] ?? 1);
$imageName = $item['image']; 
    $parts = explode('-', $imageName);
    $folder = $parts[0]; 
    $fullPath = "img/$folder/$imageName";                        
                    ?>
                    <tr>
                        <td class="text-left align-middle">
                            <img src="<?= $fullPath ?>" alt="" class="img-thumbnail mr-3"
                                style="width: 50px; height: 50px; object-fit: cover;">
                            <span class="font-weight-semi-bold"><?= htmlspecialchars($item['name']) ?></span>
                        </td>
                        <td class="align-middle font-weight-medium">
                            $<?= number_format($item['price'], 2) ?>
                        </td>
                        <td class="align-middle">
                            <div class="input-group quantity mx-auto" style="width: 100px;">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-primary btn-minus update-qty" data-id="<?= $id ?>"
                                        data-action="minus">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <input type="text"
                                    class="form-control form-control-sm bg-secondary text-center qty-input"
                                    value="<?= $item['qty'] ?>" readonly>
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-primary btn-plus update-qty" data-id="<?= $id ?>"
                                        data-action="plus">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle font-weight-bold text-dark item-total">
                            $<?= number_format($item['price'] * $item['qty'], 2) ?>
                        </td>
                        <td class="align-middle">
                            <button class="btn btn-sm btn-danger remove-item" data-id="<?= $id ?>">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="alert alert-light m-0">
                                <i class="fa fa-shopping-basket fa-3x mb-3 text-muted"></i>
                                <h4 class="text-muted">Your cart is empty!</h4>
                                <a href="shop.php" class="btn btn-primary mt-2">Start Shopping</a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="col-lg-4">
            <form action="cart.php" method="POST" class="mb-5">
                <div class="input-group">
                    <input type="text" name="coupon_code" class="form-control p-4" placeholder="Coupon Code" required>
                    <div class="input-group-append">
                        <button type="submit" name="apply_coupon" class="btn btn-primary">Apply Coupon</button>
                    </div>
                </div>
            </form>

            <?php if (!empty($coupon_msg)): ?>
            <div class="alert alert-<?= (strpos($coupon_msg, 'Invalid') !== false) ? 'danger' : 'success' ?> alert-dismissible fade show"
                role="alert">
                <?= $coupon_msg ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php endif; ?>
            <div class="card border-secondary mb-5 shadow-sm">
                <div class="card-header bg-secondary border-0">
                    <h4 class="font-weight-semi-bold m-0 text-dark">Cart Summary</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h6>Subtotal</h6>
                        <h6 id="subtotal">$<?= number_format($subtotal, 2) ?></h6>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <h6>Shipping</h6>
                        <h6>$<?= number_format($shipping, 2) ?></h6>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h6>Discount (10%)</h6>
                        <h6 id="discount-val"
                            class="font-weight-medium <?= ($coupon_rate > 0) ? 'text-success' : '' ?>">
                            -$<?= number_format($discount_amount, 2) ?>
                        </h6>
                    </div>
                </div>
                <div class="card-footer border-secondary bg-transparent">
                    <div class="d-flex justify-content-between mt-2">
                        <h5 class="font-weight-bold">Total</h5>
                        <h5 class="font-weight-bold" id="grand-total">$<?= number_format($total, 2) ?></h5>
                    </div>
                    <a href="checkout_login.php"
                        class="btn btn-block btn-primary font-weight-bold my-3 py-3 shadow">Proceed To Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/cart-logic.js"></script>
<script src="./js/actions.js"></script>
<?php require_once "./includes/footer.php";?>