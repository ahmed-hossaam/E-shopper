<?php 
    require_once "./includes/header.php";
    require_once "./includes/db.php"; 

    $msg = "";
    
    // Process contact form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Sanitize inputs to prevent XSS and malicious injections
        $name    = htmlspecialchars(strip_tags(trim($_POST['name'])));
        $email   = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $subject = htmlspecialchars(strip_tags(trim($_POST['subject'])));
        $message = htmlspecialchars(strip_tags(trim($_POST['message'])));

        // Validate required fields
        if (!empty($name) && !empty($email) && !empty($message)) {
            
            // Prepared statement to prevent SQL Injection
            $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            if ($stmt->execute([$name, $email, $subject, $message])) {
                $msg = "<div class='alert alert-success shadow-sm border-0'>
                            <i class='fa fa-check-circle mr-2'></i> 
                            <b>Success!</b> Your message has been sent.
                        </div>";
                // Reset variables to clear form fields
                $name = $email = $subject = $message = "";
            } else {
                $msg = "<div class='alert alert-danger shadow-sm border-0'>
                            <i class='fa fa-times-circle mr-2'></i> 
                            <b>Error!</b> Server error. Please try again later.
                        </div>";
            }
        } else {
            $msg = "<div class='alert alert-warning shadow-sm border-0'>
                        <i class='fa fa-exclamation-triangle mr-2'></i> 
                        <b>Wait!</b> Please fill in all required fields.
                    </div>";
        }
    }
?>

<div class="container-fluid bg-secondary mb-5">
    <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
        <h1 class="font-weight-semi-bold text-uppercase mb-3">Contact Us</h1>
        <div class="d-inline-flex">
            <p class="m-0"><a href="index.php">Home</a></p>
            <p class="m-0 px-2">-</p>
            <p class="m-0">Contact</p>
        </div>
    </div>
</div>
<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Contact For Any Queries</span></h2>
    </div>
    <div class="row px-xl-5">
        <div class="col-lg-7 mb-5">
            <div class="contact-form bg-light p-4 " style="border-radius: 10px;">
                <?= $msg; ?>

                <form action="contact.php" method="POST" novalidate="novalidate">
                    <div class="row">
                        <div class="col-md-6 control-group mb-3">
                            <label class="font-weight-medium">Your Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Full Name"
                                required="required" value="<?= isset($name) ? $name : '' ?>" />
                        </div>
                        <div class="col-md-6 control-group mb-3">
                            <label class="font-weight-medium">Your Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email Address"
                                required="required" value="<?= isset($email) ? $email : '' ?>" />
                        </div>
                    </div>
                    <div class="control-group mb-3">
                        <label class="font-weight-medium">Subject</label>
                        <input type="text" name="subject" class="form-control" placeholder="Message Subject"
                            required="required" value="<?= isset($subject) ? $subject : '' ?>" />
                    </div>
                    <div class="control-group mb-3">
                        <label class="font-weight-medium">Message</label>
                        <textarea class="form-control" name="message" rows="6" placeholder="How can we help you?"
                            required="required"><?= isset($message) ? $message : '' ?></textarea>
                    </div>
                    <div>
                        <button class="btn btn-primary py-2 px-5 font-weight-bold shadow-sm" type="submit">
                            <i class="fa fa-paper-plane mr-2"></i> Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-5 mb-5">
            <h5 class="font-weight-semi-bold mb-3">Get In Touch</h5>
            <p class="text-muted small">We value your feedback. Our team typically responds within 24 business hours.
            </p>

            <div class="d-flex flex-column mb-4 mt-4">
                <h6 class="font-weight-bold text-dark mb-3">Main Office</h6>
                <div class="d-flex align-items-center mb-2">
                    <i class="fa fa-map-marker-alt text-primary mr-3"></i>
                    <span>Tanta, Gharbia, Egypt</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="fa fa-envelope text-primary mr-3"></i>
                    <span>support@myshop.com</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fa fa-phone-alt text-primary mr-3"></i>
                    <span>+02 012 345 67890</span>
                </div>
            </div>

            <h6 class="font-weight-bold text-dark mb-3">Follow Us</h6>
            <div class="d-flex">
                <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-facebook-f"></i></a>
                <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-twitter"></i></a>
                <a class="btn btn-primary btn-square" href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
</div>

<?php require_once "./includes/footer.php"; ?>