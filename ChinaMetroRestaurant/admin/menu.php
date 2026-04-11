<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';

require_admin();

$items = get_menu_items();
$message = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $ids = $_POST['id'] ?? [];
    $numbers = $_POST['number'] ?? [];
    $names = $_POST['name'] ?? [];
    $nameAr = $_POST['name_ar'] ?? [];
    $categories = $_POST['category'] ?? [];
    $categoryAr = $_POST['category_ar'] ?? [];
    $prices = $_POST['price'] ?? [];
    $descriptions = $_POST['description'] ?? [];
    $featured = $_POST['featured'] ?? [];

    $updated = [];

    foreach ($ids as $index => $id) {
        $updated[] = [
            'id' => (int) $id,
            'number' => trim((string) ($numbers[$index] ?? '')),
            'name' => trim((string) ($names[$index] ?? '')),
            'name_ar' => trim((string) ($nameAr[$index] ?? '')),
            'category' => trim((string) ($categories[$index] ?? 'Chef Specials')),
            'category_ar' => trim((string) ($categoryAr[$index] ?? '')),
            'price' => (float) ($prices[$index] ?? 0),
            'description' => trim((string) ($descriptions[$index] ?? '')),
            'featured' => in_array((string) $id, $featured, true),
        ];
    }

    if (save_menu_items($updated)) {
        $items = get_menu_items();
        $message = 'Menu items saved successfully.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu | China Metro Restaurant</title>
    <link rel="stylesheet" href="/ChinaMetroRestaurant/assets/css/style.css">
</head>
<body>
    <div class="admin-shell">
        <div class="admin-topbar">
            <div>
                <span class="eyebrow">Admin</span>
                <h1 class="admin-title">Manage Menu</h1>
            </div>
            <div class="pill-row">
                <a class="button-secondary" href="/ChinaMetroRestaurant/admin/index.php">Dashboard</a>
                <a class="button-secondary" href="/ChinaMetroRestaurant/admin/logout.php">Log Out</a>
            </div>
        </div>
        <?php if ($message !== ''): ?>
            <div class="message success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="post" class="admin-form">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Featured</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <input type="hidden" name="id[]" value="<?= (int) $item['id'] ?>">
                                    <input type="hidden" name="number[]" value="<?= htmlspecialchars((string) ($item['number'] ?? '')) ?>">
                                    <input type="hidden" name="name_ar[]" value="<?= htmlspecialchars((string) ($item['name_ar'] ?? '')) ?>">
                                    <input type="hidden" name="category_ar[]" value="<?= htmlspecialchars((string) ($item['category_ar'] ?? '')) ?>">
                                    <input type="text" name="name[]" value="<?= htmlspecialchars((string) $item['name']) ?>">
                                </td>
                                <td><input type="text" name="category[]" value="<?= htmlspecialchars((string) $item['category']) ?>"></td>
                                <td><input type="text" name="price[]" value="<?= htmlspecialchars((string) $item['price']) ?>"></td>
                                <td><textarea name="description[]"><?= htmlspecialchars((string) $item['description']) ?></textarea></td>
                                <td><input type="checkbox" name="featured[]" value="<?= (int) $item['id'] ?>" <?= !empty($item['featured']) ? 'checked' : '' ?>></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button type="submit">Save Menu</button>
        </form>
    </div>
    <script src="/ChinaMetroRestaurant/assets/js/site.js" defer></script>
</body>
</html>
