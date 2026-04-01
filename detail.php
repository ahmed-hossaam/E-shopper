<?php 
    require_once "./includes/db.php";
    session_start();

    // 1. Fetch product by ID with basic validation
    if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: shop.php");
        exit;
    }

    $product_id = (int)$_GET['id'];

    // Securely fetch product details
    $stmt = $conn->prepare('SELECT * FROM `products` WHERE id = ?');
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC); 

    if (!$product) {
        die("Product not found!");
    }

    // 2. Handle review submission (Sanitized Logic)
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['review_text'])) {
        $p_id = (int)$_POST['product_id'];
        $name = htmlspecialchars(strip_tags(trim($_POST['user_name'])));
        $email = filter_var(trim($_POST['user_email']), FILTER_SANITIZE_EMAIL);
        $rating = (int)$_POST['rating'];
        $review_text = htmlspecialchars(strip_tags(trim($_POST['review_text'])));

        if ($rating >= 1 && $rating <= 5 && !empty($name) && !empty($review_text)) {
            $stmt = $conn->prepare("INSERT INTO `reviews` (`product_id`, `user_name`, `user_email`, `rating`, `review_text`, `status`) VALUES (?,?,?,?,?, 1)");
            $result = $stmt->execute([$p_id, $name, $email, $rating, $review_text]);

            if ($result) {
                header("Location: detail.php?id=" . $p_id . "&status=success#tab-pane-3");
                exit;
            }
        }
    }

    // 3. Fetch related products from the same category
    $stmt_rel = $conn->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? LIMIT 10");
    $stmt_rel->execute([$product['category_id'], $product['id']]);
    $related_products = $stmt_rel->fetchAll(PDO::FETCH_ASSOC);

    // 4. Fetch approved reviews and count them
    $stmt_rev = $conn->prepare("SELECT * FROM `reviews` WHERE product_id = ? AND status = 1 ORDER BY created_at DESC");
    $stmt_rev->execute([$product_id]);
    $reviews = $stmt_rev->fetchAll(PDO::FETCH_ASSOC);
    $counts_reviews = count($reviews);

    require_once "./includes/header.php";
?>

<style>
/* Your Original Styles */
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.star-rating input {
    display: none;
}

.star-rating label {
    font-size: 25px;
    color: #ccc;
    padding: 2px;
    cursor: pointer;
    transition: 0.2s;
}

.star-rating input:checked~label,
.star-rating label:hover,
.star-rating label:hover~label,
.media-body .star-rating-tab {
    color: #FFD333 !important;
}

.main-product-img {
    max-height: 500px;
    object-fit: contain;
    width: 100%;
}
</style>

<div class="container-fluid bg-secondary mb-5">
    <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
        <h1 class="font-weight-semi-bold text-uppercase mb-3">Product Detail</h1>
        <div class="d-inline-flex">
            <p class="m-0"><a href="index.php">Home</a></p>
            <p class="m-0 px-2">-</p>
            <p class="m-0">Detail</p>
        </div>
    </div>
</div>

<div class="container-fluid py-5">
    <div class="row px-xl-5">
        <div class="col-lg-5 pb-5">
            <?php 
                $imageName = $product['image']; 
                $folder = explode('-', $imageName)[0];
                $imagePath = "img/$folder/$imageName";
            ?>
            <div id="product-carousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner border">
                    <div class="carousel-item active">
                        <img class="main-product-img" src="<?= htmlspecialchars($imagePath) ?>"
                            alt="<?= htmlspecialchars($product['name']) ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7 pb-5">
            <h3 class="font-weight-semi-bold"><?= htmlspecialchars($product['name']) ?></h3>
            <div class="d-flex mb-3">
                <div class="text-primary mr-2">
                    <?php 
                    $avg_rating = 0;
                    if ($counts_reviews > 0) {
                        $total_rating = array_sum(array_column($reviews, 'rating'));
                        $avg_rating = $total_rating / $counts_reviews;
                    }
                    
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= floor($avg_rating)) {
                            echo '<small class="fas fa-star" style="color: #FFD333;"></small>';
                        } else {
                            echo '<small class="far fa-star" style="color: #ccc;"></small>';
                        }
                    }
                    ?>
                </div>
                <small class="pt-1">(<?= $counts_reviews ?> Reviews)</small>
            </div>
            <h3 class="font-weight-semi-bold mb-1">$<?= number_format($product['price'], 2) ?></h3>
            <h4 class="text-muted"><del>$<?= number_format($product['price'] * 1.5, 2) ?></del></h4>
            <p class="mb-4 mt-3"><?= htmlspecialchars($product['product_description']) ?></p>

            <div class="d-flex mb-3">
                <p class="text-dark font-weight-medium mb-0 mr-3">Sizes:</p>
                <form>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="size-1" name="size">
                        <label class="custom-control-label" for="size-1">XS</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="size-2" name="size">
                        <label class="custom-control-label" for="size-2">S</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="size-3" name="size">
                        <label class="custom-control-label" for="size-3">M</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="size-4" name="size">
                        <label class="custom-control-label" for="size-4">L</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="size-5" name="size">
                        <label class="custom-control-label" for="size-5">XL</label>
                    </div>
                </form>
            </div>
            <div class="d-flex mb-4">
                <p class="text-dark font-weight-medium mb-0 mr-3">Colors:</p>
                <form>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="color-1" name="color">
                        <label class="custom-control-label" for="color-1">Black</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="color-2" name="color">
                        <label class="custom-control-label" for="color-2">White</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="color-3" name="color">
                        <label class="custom-control-label" for="color-3">Red</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="color-4" name="color">
                        <label class="custom-control-label" for="color-4">Blue</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" class="custom-control-input" id="color-5" name="color">
                        <label class="custom-control-label" for="color-5">Green</label>
                    </div>
                </form>
            </div>

            <div class="d-flex align-items-center mb-4 pt-2">
                <div class="input-group quantity mr-3" style="width: 130px;">
                    <div class="input-group-btn">
                        <button class="btn btn-primary btn-minus"><i class="fa fa-minus"></i></button>
                    </div>
                    <input type="text" id="product-qty" class="form-control bg-secondary text-center" value="1">
                    <div class="input-group-btn">
                        <button class="btn btn-primary btn-plus"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <button onclick="addToCart(<?= (int)$product['id'] ?>)" class="btn btn-primary px-3">
                    <i class="fa fa-shopping-cart mr-1"></i> Add To Cart
                </button>
            </div>
        </div>
    </div>

    <div class="row px-xl-5">
        <div class="col">
            <div class="nav nav-tabs justify-content-center border-secondary mb-4">
                <a class="nav-item nav-link active" data-toggle="tab" href="#tab-pane-1">Description</a>
                <a class="nav-item nav-link" data-toggle="tab" href="#tab-pane-3">Reviews (<?= $counts_reviews ?>)</a>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab-pane-1">
                    <h4 class="mb-3">Product Description</h4>
                    <p><?= htmlspecialchars($product['product_description']) ?></p>
                </div>

                <div class="tab-pane fade" id="tab-pane-3">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-4"><?= $counts_reviews ?> reviews for
                                "<?= htmlspecialchars($product['name']) ?>"</h4>
                            <?php if ($counts_reviews == 0) : ?>
                            <p class="alert alert-info">No reviews yet. Be the first!</p>
                            <?php else : ?>
                            <?php foreach($reviews as $review) :?>
                            <div class="media mb-4">
                                <div class="media-body">
                                    <h6><?= htmlspecialchars($review['user_name']) ?><small> -
                                            <i><?= date('d M Y', strtotime($review['created_at'])) ?></i></small></h6>
                                    <div class="mb-2 star-rating-tab">
                                        <?php for($i=1; $i<=5; $i++) echo ($i <= $review['rating']) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'; ?>
                                    </div>
                                    <p><?= htmlspecialchars($review['review_text']) ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <h4 class="mb-4">Leave a review</h4>
                            <form action="" method="POST">
                                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                                <div class="form-group d-flex align-items-center">
                                    <label class="mb-0 mr-3">Your Rating * :</label>
                                    <div class="star-rating">
                                        <input type="radio" name="rating" id="star5" value="5" required><label
                                            for="star5">★</label>
                                        <input type="radio" name="rating" id="star4" value="4"><label
                                            for="star4">★</label>
                                        <input type="radio" name="rating" id="star3" value="3"><label
                                            for="star3">★</label>
                                        <input type="radio" name="rating" id="star2" value="2"><label
                                            for="star2">★</label>
                                        <input type="radio" name="rating" id="star1" value="1"><label
                                            for="star1">★</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Your Review *</label>
                                    <textarea name="review_text" rows="5" class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Your Name *</label>
                                    <input type="text" name="user_name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Your Email *</label>
                                    <input type="email" name="user_email" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary px-3">Leave Your Review</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">You May Also Like</span></h2>
    </div>
    <div class="row px-xl-5">
        <div class="col">
            <div class="owl-carousel related-carousel">
                <?php foreach ($related_products as $rel_prod): 
                    $relImageName = $rel_prod['image']; 
                    $relFolder = explode('-', $relImageName)[0];
                    $relImagePath = "img/$relFolder/$relImageName";
                ?>
                <div class="card product-item border-0">
                    <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                        <img class="img-fluid w-100" src="<?= htmlspecialchars($relImagePath) ?>"
                            alt="<?= htmlspecialchars($rel_prod['name']) ?>">
                    </div>
                    <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                        <h6 class="text-truncate mb-3"><?= htmlspecialchars($rel_prod['name']) ?></h6>
                        <div class="d-flex justify-content-center">
                            <h6>$<?= number_format($rel_prod['price'], 2) ?></h6>
                            <h6 class="text-muted ml-2"><del>$<?= number_format($rel_prod['price'] * 1.5, 2) ?></del>
                            </h6>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between bg-light border">
                        <a href="detail.php?id=<?= (int)$rel_prod['id'] ?>" class="btn btn-sm text-dark p-0"><i
                                class="fas fa-eye text-primary mr-1"></i>View</a>
                        <a href="add_to_cart.php?id=<?= (int)$rel_prod['id'] ?>&qty=1"
                            class="btn btn-sm text-dark p-0"><i
                                class="fas fa-shopping-cart text-primary mr-1"></i>Add</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
function addToCart(productId) {
    let qty = document.getElementById('product-qty').value;
    window.location.href = `add_to_cart.php?id=${productId}&qty=${qty}`;
}
</script>

<?php require_once "./includes/footer.php"; ?>