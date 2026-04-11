<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

if (current_customer()) {
    redirect_to('/ChinaMetroRestaurant/profile.php');
}

$site = get_site_content();
$errors = [];
$values = [
    'full_name' => '',
    'email' => '',
];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $values['full_name'] = trim((string) ($_POST['full_name'] ?? ''));
    $values['email'] = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    if ($values['full_name'] === '' || $values['email'] === '' || $password === '' || $confirmPassword === '') {
        $errors[] = 'All fields are required.';
    }

    if ($values['email'] !== '' && !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($password !== '' && strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Password and Confirm Password must match.';
    }

    if ($values['email'] !== '' && get_user_by_email($values['email']) !== null) {
        $errors[] = 'An account with this email already exists.';
    }

    if ($errors === []) {
        if (create_user_account($values['full_name'], $values['email'], $password)) {
            redirect_to('/ChinaMetroRestaurant/login.php?registered=1');
        }

        $errors[] = 'Registration could not be completed. Please try again.';
    }
}

render_header('Register', $site, 'register');
?>
<section class="page-hero">
    <span class="eyebrow">Customer Account</span>
    <h1 class="page-title">Create your China Metro account</h1>
    <p class="lead">Register with your details so you are ready for future ordering and order history features.</p>
</section>

<section class="section">
    <div class="contact-grid">
        <article class="contact-card">
            <h2>Why register?</h2>
            <p>Creating an account makes it easier to continue into customer features like orders, saved details, and order history as the site grows.</p>
            <p><strong>Validation included:</strong> empty-field checks, email format validation, password length checks, password confirmation matching, and duplicate email protection.</p>
        </article>
        <article class="contact-card">
            <h2>Register</h2>
            <?php if ($errors !== []): ?>
                <div class="message error"><?= htmlspecialchars(implode(' ', $errors)) ?></div>
            <?php endif; ?>
            <form class="contact-form" method="post" novalidate>
                <label>
                    Full Name
                    <input type="text" name="full_name" required value="<?= htmlspecialchars($values['full_name']) ?>">
                </label>
                <label>
                    Email
                    <input type="email" name="email" required value="<?= htmlspecialchars($values['email']) ?>">
                </label>
                <label>
                    Password
                    <input type="password" name="password" required minlength="8">
                </label>
                <label>
                    Confirm Password
                    <input type="password" name="confirm_password" required minlength="8">
                </label>
                <button type="submit">Create Account</button>
            </form>
        </article>
    </div>
</section>
<?php render_footer($site); ?>
