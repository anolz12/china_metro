<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

require_admin();

$site = get_site_content();
$message = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $site = [
        'name' => trim((string) ($_POST['name'] ?? 'China Metro Restaurant')),
        'tagline' => trim((string) ($_POST['tagline'] ?? '')),
        'hero_title' => trim((string) ($_POST['hero_title'] ?? '')),
        'hero_text' => trim((string) ($_POST['hero_text'] ?? '')),
        'about' => trim((string) ($_POST['about'] ?? '')),
        'address' => trim((string) ($_POST['address'] ?? '')),
        'phone' => trim((string) ($_POST['phone'] ?? '')),
        'email' => trim((string) ($_POST['email'] ?? '')),
        'hours' => trim((string) ($_POST['hours'] ?? '')),
        'footer_blurb' => trim((string) ($_POST['footer_blurb'] ?? '')),
    ];

    if (save_site_content($site)) {
        $message = 'Restaurant information updated successfully.';
        $site = get_site_content();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Settings | China Metro Restaurant</title>
    <link rel="stylesheet" href="/ChinaMetroRestaurant/assets/css/style.css">
</head>
<body>
    <div class="admin-shell" style="max-width: 900px;">
        <div class="admin-topbar">
            <div>
                <span class="eyebrow">Admin</span>
                <h1 class="admin-title">Restaurant Information</h1>
            </div>
            <div class="pill-row">
                <a class="button-secondary" href="/ChinaMetroRestaurant/admin/index.php">Dashboard</a>
                <a class="button-secondary" href="/ChinaMetroRestaurant/admin/logout.php">Log Out</a>
            </div>
        </div>
        <?php if ($message !== ''): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form class="admin-form" method="post">
            <label>
                Restaurant Name
                <input type="text" name="name" value="<?= htmlspecialchars((string) $site['name']) ?>">
            </label>
            <label>
                Tagline
                <input type="text" name="tagline" value="<?= htmlspecialchars((string) $site['tagline']) ?>">
            </label>
            <label>
                Hero Title
                <input type="text" name="hero_title" value="<?= htmlspecialchars((string) $site['hero_title']) ?>">
            </label>
            <label>
                Hero Text
                <textarea name="hero_text"><?= htmlspecialchars((string) $site['hero_text']) ?></textarea>
            </label>
            <label>
                About
                <textarea name="about"><?= htmlspecialchars((string) $site['about']) ?></textarea>
            </label>
            <label>
                Address
                <textarea name="address"><?= htmlspecialchars((string) $site['address']) ?></textarea>
            </label>
            <label>
                Phone
                <input type="text" name="phone" value="<?= htmlspecialchars((string) $site['phone']) ?>">
            </label>
            <label>
                Email
                <input type="email" name="email" value="<?= htmlspecialchars((string) $site['email']) ?>">
            </label>
            <label>
                Hours
                <textarea name="hours"><?= htmlspecialchars((string) $site['hours']) ?></textarea>
            </label>
            <label>
                Footer Blurb
                <textarea name="footer_blurb"><?= htmlspecialchars((string) $site['footer_blurb']) ?></textarea>
            </label>
            <button type="submit">Save Restaurant Info</button>
        </form>
    </div>
    <script src="/ChinaMetroRestaurant/assets/js/site.js" defer></script>
</body>
</html>
