<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

function asset_url(string $path): string
{
    return '/ChinaMetroRestaurant/' . ltrim($path, '/');
}

function render_header(string $title, array $site, string $activePage = 'home'): void
{
    $siteName = htmlspecialchars((string) ($site['name'] ?? 'China Metro Restaurant'));
    $tagline = htmlspecialchars((string) ($site['tagline'] ?? 'Bold flavours, warm hospitality.'));
    $customer = current_customer();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> | <?= $siteName ?></title>
    <link rel="stylesheet" href="<?= asset_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="site-shell">
        <header class="topbar">
            <a class="brand" href="<?= asset_url('index.php') ?>">
                <img src="<?= asset_url('assets/images/logo.jpg') ?>" alt="China Metro Restaurant logo">
                <div>
                    <span class="brand-title"><?= $siteName ?></span>
                    <span class="brand-tagline"><?= $tagline ?></span>
                </div>
            </a>
            <nav class="nav">
                <a class="<?= $activePage === 'home' ? 'active' : '' ?>" href="<?= asset_url('index.php') ?>">Home</a>
                <a class="<?= $activePage === 'menu' ? 'active' : '' ?>" href="<?= asset_url('menu.php') ?>">Menu</a>
                <a class="<?= $activePage === 'offers' ? 'active' : '' ?>" href="<?= asset_url('offers.php') ?>">Offers</a>
                <a class="<?= $activePage === 'contact' ? 'active' : '' ?>" href="<?= asset_url('contact.php') ?>">Contact</a>
                <?php if ($customer): ?>
                    <a class="<?= $activePage === 'profile' ? 'active' : '' ?>" href="<?= asset_url('profile.php') ?>">Profile</a>
                    <a href="<?= asset_url('logout.php') ?>">Logout</a>
                <?php else: ?>
                    <a class="<?= $activePage === 'login' ? 'active' : '' ?>" href="<?= asset_url('login.php') ?>">Login</a>
                    <a class="<?= $activePage === 'register' ? 'active' : '' ?>" href="<?= asset_url('register.php') ?>">Register</a>
                <?php endif; ?>
                <a class="nav-admin" href="<?= asset_url('admin/login.php') ?>">Admin</a>
            </nav>
        </header>
        <main>
<?php
}

function render_footer(array $site): void
{
    $siteName = htmlspecialchars((string) ($site['name'] ?? 'China Metro Restaurant'));
    $address = nl2br(htmlspecialchars((string) ($site['address'] ?? '')));
    $phone = htmlspecialchars((string) ($site['phone'] ?? ''));
    $email = htmlspecialchars((string) ($site['email'] ?? ''));
    ?>
        </main>
        <footer class="footer">
            <div>
                <h3><?= $siteName ?></h3>
                <p><?= htmlspecialchars((string) ($site['footer_blurb'] ?? 'Modern Chinese and Indo-Chinese dining with a memorable atmosphere.')) ?></p>
            </div>
            <div>
                <h4>Visit Us</h4>
                <p><?= $address ?></p>
            </div>
            <div>
                <h4>Contact</h4>
                <p><?= $phone ?><br><?= $email ?></p>
            </div>
        </footer>
    </div>
    <script src="<?= asset_url('assets/js/site.js') ?>" defer></script>
</body>
</html>
<?php
}
