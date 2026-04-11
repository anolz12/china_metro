<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

if (current_admin()) {
    redirect_to('/ChinaMetroRestaurant/admin/index.php');
}

$error = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (!attempt_login($username, $password)) {
        $error = 'Invalid username or password.';
    } else {
        redirect_to('/ChinaMetroRestaurant/admin/index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | China Metro Restaurant</title>
    <link rel="stylesheet" href="/ChinaMetroRestaurant/assets/css/style.css">
</head>
<body>
    <div class="admin-shell" style="max-width: 520px;">
        <div class="admin-topbar">
            <div>
                <span class="eyebrow">Admin</span>
                <h1 class="admin-title">Sign in</h1>
            </div>
            <a class="button-secondary" href="/ChinaMetroRestaurant/index.php">Back to site</a>
        </div>
        <div class="admin-card">
            <?php if ($error !== ''): ?>
                <div class="message error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form class="admin-form" method="post">
                <label>
                    Username
                    <input type="text" name="username" required>
                </label>
                <label>
                    Password
                    <input type="password" name="password" required>
                </label>
                <button type="submit">Log In</button>
            </form>
        </div>
    </div>
    <script src="/ChinaMetroRestaurant/assets/js/site.js" defer></script>
</body>
</html>
