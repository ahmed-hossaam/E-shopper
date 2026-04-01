<?php
require_once "./includes/header.php";

$ranges = [
    '0-100'   => '$0 - $100',
    '100-200' => '$100 - $200',
    '200-300' => '$200 - $300',
    '300-400' => '$300 - $400',
    '400-500' => '$400 - $500'
];

$price_range = $_GET['price_range'] ?? 'all';
$id = $_GET['id'] ?? 'all';
?>

<style>
.custom-control-input:checked~.custom-control-label {
    color: $primary !important;
    font-weight: bold;
}

.cat-pill-item.active {

    color: white !important;
    border-radius: 20px;
    padding: 5px 10px;
}

.cat-pill-item {
    cursor: pointer;
    margin-right: 1px;
    text-decoration: none !important;
    display: inline-block;
    padding: 5px 15px;
    color: #666;
}

#products-grid {
    min-height: 400px;
}
</style>
<div class="container-fluid bg-secondary mb-5">
    <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
        <h1 class="font-weight-semi-bold text-uppercase mb-3">OUR Shop</h1>
        <div class="d-inline-flex">
            <p class="m-0"><a href="index.php">Home</a></p>
            <p class="m-0 px-2">-</p>
            <p class="m-0">Shop</p>
        </div>
    </div>
</div>
<div class="container-fluid pt-5">
    <div class="row px-xl-5">
        <div class="col-lg-3 col-md-12">
            <div class="mb-4 pb-4">
                <h5 class="font-weight-semi-bold mb-4">Filter by price</h5>
                <div id="price-filter-container">
                    <div class="custom-control custom-radio d-flex align-items-center justify-content-between mb-3">
                        <input type="radio" name="price_range" value="all" class="custom-control-input filter-trigger"
                            id="price-all" <?= ($price_range == 'all') ? 'checked' : '' ?>>
                        <label class="custom-control-label text-dark" for="price-all">All Price</label>
                    </div>
                    <?php $i = 1; foreach($ranges as $val => $label): ?>
                    <div class="custom-control custom-radio d-flex align-items-center justify-content-between mb-3">
                        <input type="radio" name="price_range" value="<?= $val ?>"
                            class="custom-control-input filter-trigger" id="price-<?= $i ?>"
                            <?= ($price_range == $val) ? 'checked' : '' ?>>
                        <label class="custom-control-label text-dark" for="price-<?= $i ?>"><?= $label ?></label>
                    </div>
                    <?php $i++; endforeach; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-12">
            <div class="row pb-3">
                <div class="col-12 mb-4">
                    <div class="cat-pills" id="category-container">
                        <span class="font-weight-bold mr-2">Categories:</span>
                        <a class="cat-pill-item filter-trigger <?= ($id == 'all') ? 'active' : '' ?>"
                            data-type="category" data-val="all">All Products</a>
                        <?php foreach ($categories as $cat): ?>
                        <a class="cat-pill-item filter-trigger <?= ($id == $cat['id']) ? 'active' : '' ?>"
                            data-type="category" data-val="<?= $cat['id'] ?>"><?= $cat['name'] ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-12 pb-1">
                    <div
                        class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-4 shop-controls">
                        <div class="input-group" style="max-width: 400px;">
                            <input type="text" id="search-input" class="form-control" placeholder="Search by name">
                            <div class="input-group-append">
                                <button class="input-group-text bg-transparent text-primary" id="search-btn"><i
                                        class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <!-- <div class="dropdown ml-4">
                            <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown"
                                id="sort-label">
                                Sort by: Latest
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item filter-trigger active-sort" data-type="sort" data-val="latest"
                                    href="#">Latest</a>
                                <a class="dropdown-item filter-trigger" data-type="sort" data-val="price-asc"
                                    href="#">Price Low to High</a>
                                <a class="dropdown-item filter-trigger" data-type="sort" data-val="price-desc"
                                    href="#">Price High to Low</a>
                            </div>
                        </div> -->
                    </div>
                </div>

                <div class="row w-100" id="products-grid">
                </div>

                <div class="col-12 pb-1 mt-4">
                    <nav>
                        <ul class="pagination justify-content-center" id="pagination-container"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./js/shop-logic.js"></script>
<?php require_once "./includes/footer.php"; ?>