<?php require_once "./includes/header.php"; ?>

<div class="container-fluid bg-secondary mb-5">
    <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
        <h1 class="font-weight-semi-bold text-uppercase mb-3">About Us</h1>
        <div class="d-inline-flex">
            <p class="m-0"><a href="index.php">Home</a></p>
            <p class="m-0 px-2">-</p>
            <p class="m-0">About Us</p>
        </div>
    </div>
</div>
<section class="about-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 about-content" data-aos="fade-up">
                <span class="badge rounded-pill bg-primary-soft text-primary px-3 py-2 mb-3">Our Identity</span>
                <h1 class="display-4 fw-bold mb-4">Crafting Excellence <br> <span class="text-primary">Since 2025</span>
                </h1>
                <p class="lead text-muted mb-4">We don't just sell products; we deliver experiences. Founded in the
                    halls of Applied Technology, we've grown into a digital powerhouse.</p>
                <div class="d-flex gap-3 mb-5 justify-content-between">
                    <div class="stat-card">
                        <h4 class="fw-bold mb-0">10k+</h4>
                        <small class="text-muted">Happy Clients</small>
                    </div>
                    <div class="stat-card border-start">
                        <h4 class="fw-bold mb-0">500+</h4>
                        <small class="text-muted">Premium Goods</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 position-relative">
                <div class="image-stack">
                    <img src="./img/team-about.avif" class="img-main shadow-lg" alt="Team">
                    <img src="./img/support-about.avif" class="img-sub shadow-lg" alt="Support">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="features-grid py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-box p-4 text-center">
                    <div class="icon-circle mb-3"><i class="fas fa-shield-alt"></i></div>
                    <h5>Authentic Quality</h5>
                    <p class="text-muted">Every product is verified by our expert team for 100% authenticity.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box p-4 text-center active">
                    <div class="icon-circle mb-3"><i class="fas fa-shipping-fast"></i></div>
                    <h5>Express Delivery</h5>
                    <p class="text-muted">Fast shipping that tracks your order from our warehouse to your door.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box p-4 text-center">
                    <div class="icon-circle mb-3"><i class="fas fa-headset"></i></div>
                    <h5>Expert Support</h5>
                    <p class="text-muted">Our specialized support team is available 24/7 for your needs.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once "./includes/footer.php"; ?>