<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

require_admin();

$offers = get_offers();
$message = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $titles = $_POST['title'] ?? [];
    $labels = $_POST['label'] ?? [];
    $descriptions = $_POST['description'] ?? [];
    $validities = $_POST['validity'] ?? [];
    $activeIds = $_POST['active'] ?? [];
    $updated = [];

    foreach ($titles as $index => $title) {
        $updated[] = [
            'id' => $index + 1,
            'title' => trim((string) $title),
            'label' => trim((string) ($labels[$index] ?? '')),
            'description' => trim((string) ($descriptions[$index] ?? '')),
            'validity' => trim((string) ($validities[$index] ?? '')),
            'active' => in_array((string) ($index + 1), $activeIds, true),
        ];
    }

    if (save_offers($updated)) {
        $offers = get_offers();
        $message = 'Offers updated successfully.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Offers | China Metro Restaurant</title>
    <link rel="stylesheet" href="/ChinaMetroRestaurant/assets/css/style.css">
</head>
<body>
    <div class="admin-shell">
        <div class="admin-topbar">
            <div>
                <span class="eyebrow">Admin</span>
                <h1 class="admin-title">Manage Offers</h1>
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
            <?php foreach ($offers as $index => $offer): ?>
                <article class="admin-card">
                    <label>
                        Title
                        <input type="text" name="title[]" value="<?= htmlspecialchars((string) $offer['title']) ?>">
                    </label>
                    <label>
                        Label
                        <input type="text" name="label[]" value="<?= htmlspecialchars((string) $offer['label']) ?>">
                    </label>
                    <label>
                        Description
                        <textarea name="description[]"><?= htmlspecialchars((string) $offer['description']) ?></textarea>
                    </label>
                    <label>
                        Validity
                        <input type="text" name="validity[]" value="<?= htmlspecialchars((string) $offer['validity']) ?>">
                    </label>
                    <label>
                        <input type="checkbox" name="active[]" value="<?= $index + 1 ?>" <?= !empty($offer['active']) ? 'checked' : '' ?>>
                        Keep this offer visible on the website
                    </label>
                </article>
            <?php endforeach; ?>
            <button type="submit">Save Offers</button>
        </form>
    </div>
    <script src="/ChinaMetroRestaurant/assets/js/site.js" defer></script>
</body>
</html>
