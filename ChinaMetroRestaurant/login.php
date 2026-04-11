<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$site = get_site_content();
$error = '';
$success = isset($_GET['registered']) ? 'Registration successful. Please log in with your new account.' : '';

if (current_customer()) {
    redirect_to('/ChinaMetroRestaurant/profile.php');
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Please enter both email and password.';
    } elseif (!attempt_customer_login($email, $password)) {
        $error = 'Invalid email or password.';
    } else {
        redirect_to('/ChinaMetroRestaurant/profile.php');
    }
}

render_header('Login', $site, 'login');
?>
<section class="page-hero">
    <span class="eyebrow">Customer Login</span>
    <h1 class="page-title">Sign in to your account</h1>
    <p class="lead">Use your registered email and password to access your customer account.</p>
</section>

<section class="section">
    <div class="contact-grid">
        <article class="contact-card">
            <h2>Login</h2>
            <?php if ($success !== ''): ?>
                <div class="message success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if ($error !== ''): ?>
                <div class="message error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form class="contact-form" method="post">
                <label>
                    Email
                    <input type="email" name="email" required>
                </label>
                <label>
                    Password
                    <input type="password" name="password" required>
                </label>
                <button type="submit">Log In</button>
            </form>
        </article>
        <article class="contact-card">
            <h2>New here?</h2>
            <p>Create an account in a few seconds and keep your profile ready for future ordering and order history features.</p>
            <a class="button-secondary" href="<?= asset_url('register.php') ?>">Go to Register</a>
        </article>
    </div>
</section>
<?php render_footer($site); ?>
