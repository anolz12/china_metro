<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

require_admin();

$site = get_site_content();
$menuItems = get_menu_items();
$offers = get_offers();
$contacts = get_contacts();
$admin = current_admin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | China Metro Restaurant</title>
    <link rel="stylesheet" href="/ChinaMetroRestaurant/assets/css/style.css">
</head>
<body>
    <div class="admin-shell">
        <div class="admin-topbar">
            <div>
                <span class="eyebrow">Dashboard</span>
                <h1 class="admin-title">Welcome, <?= htmlspecialchars((string) ($admin['name'] ?? 'Admin')) ?></h1>
                <p class="muted">Manage menu content, offers, restaurant information, and contact submissions from one place.</p>
            </div>
            <div class="pill-row">
                <a class="button-secondary" href="/ChinaMetroRestaurant/index.php">View Site</a>
                <a class="button-secondary" href="/ChinaMetroRestaurant/admin/logout.php">Log Out</a>
            </div>
        </div>

        <div class="admin-actions">
            <a class="admin-card" href="/ChinaMetroRestaurant/admin/menu.php">
                <span class="eyebrow">Menu</span>
                <h2><?= count($menuItems) ?> items</h2>
                <p class="muted">Update dish names, categories, prices, descriptions, and featured flags.</p>
            </a>
            <a class="admin-card" href="/ChinaMetroRestaurant/admin/offers.php">
                <span class="eyebrow">Offers</span>
                <h2><?= count($offers) ?> promotions</h2>
                <p class="muted">Refresh active deals and keep the homepage current.</p>
            </a>
            <a class="admin-card" href="/ChinaMetroRestaurant/admin/settings.php">
                <span class="eyebrow">Restaurant Info</span>
                <h2><?= htmlspecialchars((string) $site['name']) ?></h2>
                <p class="muted">Edit address, contact details, hero copy, and operating hours.</p>
            </a>
            <div class="admin-card">
                <span class="eyebrow">Contact Inbox</span>
                <h2><?= count($contacts) ?> submissions</h2>
                <p class="muted">Recent customer messages appear below.</p>
            </div>
        </div>

        <div class="admin-grid">
            <?php foreach (array_slice($contacts, 0, 6) as $contact): ?>
                <article class="admin-card">
                    <h3><?= htmlspecialchars((string) $contact['name']) ?></h3>
                    <p class="compact"><?= htmlspecialchars((string) $contact['phone']) ?><?php if (!empty($contact['email'])): ?> | <?= htmlspecialchars((string) $contact['email']) ?><?php endif; ?></p>
                    <p><?= htmlspecialchars((string) $contact['note']) ?></p>
                    <p class="muted"><?= htmlspecialchars((string) $contact['submitted_at']) ?></p>
                </article>
            <?php endforeach; ?>
            <?php if ($contacts === []): ?>
                <article class="admin-card">
                    <h3>No enquiries yet</h3>
                    <p class="muted">Messages submitted from the contact page will appear here.</p>
                </article>
            <?php endif; ?>
        </div>
    </div>
    <script src="/ChinaMetroRestaurant/assets/js/site.js" defer></script>
</body>
</html>
