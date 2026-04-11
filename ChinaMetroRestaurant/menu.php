<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$site = get_site_content();
$menuItems = get_menu_items();
$grouped = menu_categories($menuItems);

render_header('Menu', $site, 'menu');
?>
<section class="page-hero">
    <span class="eyebrow">Menu</span>
    <h1 class="page-title">Browse the China Metro menu</h1>
    <p class="lead">Real menu items imported from your spreadsheet, organized by category for quick browsing.</p>
</section>

<section class="section">
    <div class="pill-row">
        <?php foreach (array_keys($grouped) as $category): ?>
            <a class="pill" href="#<?= rawurlencode($category) ?>"><?= htmlspecialchars($category) ?></a>
        <?php endforeach; ?>
    </div>

    <?php foreach ($grouped as $category => $items): ?>
        <div class="category-block" id="<?= rawurlencode($category) ?>">
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Category</span>
                    <h2><?= htmlspecialchars($category) ?></h2>
                </div>
                <p><?= count($items) ?> items</p>
            </div>
            <div class="menu-grid">
                <?php foreach ($items as $item): ?>
                    <article class="menu-card">
                        <div class="menu-card-top">
                            <div>
                                <h3><?= htmlspecialchars((string) $item['name']) ?></h3>
                            </div>
                            <span class="price"><?= currency_format((float) $item['price']) ?></span>
                        </div>
                        <p><?= htmlspecialchars((string) $item['description']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</section>
<?php render_footer($site); ?>
