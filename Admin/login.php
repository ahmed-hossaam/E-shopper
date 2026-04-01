<?php
session_start();
require_once "../includes/db.php";

if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";
if (isset($_POST['login_btn'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin' LIMIT 1");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            header("Location: index.php");
            exit;
        } else {
$error = "Invalid email address or password. Please try again.";        }
    }
}
require_once "sidebar.php";
?>

<style>
:root {
    --login-width: 100%;
    --login-height: 100%;
}

body {
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background: var(--color-background);
    font-family: 'Poppins', sans-serif;
}

.login-main {
    display: flex;
    width: var(--login-width);
    height: var(--login-height);
    background: var(--color-white);
    border-radius: 3rem;
    box-shadow: 0 2rem 4rem var(--color-light);
    overflow: hidden;
    transition: all 0.3s ease;
}

.login-left {
    flex: 1;
    background: linear-gradient(225deg, var(--color-primary), #4158d0);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: white;
    padding: 3rem;
    text-align: center;
}

.login-left i {
    font-size: 6rem;
    margin-bottom: 1.5rem;
    opacity: 0.9;
}

.login-left h1 {
    font-size: 3rem;
    font-weight: 800;
    letter-spacing: 2px;
}

.login-left p {
    font-size: 1.1rem;
    opacity: 0.8;
    margin-top: 1rem;
}

.login-right {
    flex: 1.2;
    padding: 4rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.login-right h2 {
    font-size: 2.2rem;
    color: var(--color-dark);
    margin-bottom: 0.5rem;
}

.login-right p.subtitle {
    color: var(--color-info-dark);
    margin-bottom: 2.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.7rem;
    font-weight: 600;
    color: var(--color-dark);
    font-size: 0.95rem;
}

.form-group input {
    width: 100%;
    padding: 1.2rem;
    background: var(--color-background);
    border: 1px solid var(--color-info-light);
    border-radius: 1.2rem;
    font-size: 1rem;
    color: var(--color-dark);
    transition: all 0.3s ease;
}

.form-group input:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 4px rgba(115, 128, 236, 0.1);
    outline: none;
}

.login-btn {
    width: 100%;
    padding: 1.2rem;
    background: var(--color-primary);
    color: white;
    border: none;
    border-radius: 1.2rem;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    margin-top: 1.5rem;
    box-shadow: 0 1rem 2rem rgba(115, 128, 236, 0.2);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.login-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 1.5rem 2.5rem rgba(115, 128, 236, 0.3);
}

.error-alert {
    background: #ff7782;
    color: white;
    padding: 1rem;
    border-radius: 1rem;
    margin-bottom: 2rem;
    text-align: center;
    font-weight: 500;
}

@media (max-width: 1024px) {
    .login-main {
        width: 95%;
        height: auto;
        flex-direction: column;
    }

    .login-left {
        padding: 2rem;
    }

    .login-right {
        padding: 2.5rem;
    }
}

main {
    padding: 50px;
}
</style>

<main>

    <div class="login-main">
        <div class="login-left">
            <i class="fas fa-user-shield"></i>
            <h1>WELCOME</h1>
            <p>E-SHOPPER ADMINISTRATION SYSTEM</p>
        </div>

        <div class="login-right">
            <h2>Admin Login</h2>
            <p class="subtitle">Enter your credentials to access the panel.</p>

            <?php if($error): ?>
            <div class="error-alert">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="admin@eshop.com" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" name="login_btn" class="login-btn">
                    Access Dashboard <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

</main>
</body>

</html>