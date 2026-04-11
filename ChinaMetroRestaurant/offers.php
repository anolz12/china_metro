<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$site = get_site_content();
$offers = get_offers();

render_header('Offers', $site, 'offers');
?>
<section class="page-hero">
    <span class="eyebrow">Offers</span>
    <h1 class="page-title">Promotions that bring guests back</h1>
    <p class="lead">Use these offers to improve reach, support branding, and give customers a better reason to choose China Metro.</p>
</section>

<section class="section">
    <div class="offer-grid">
        <?php foreach ($offers as $index => $offer): ?>
            <?php if (empty($offer['active'])) continue; ?>
            <article class="offer-card <?= $index === 0 ? 'featured' : '' ?>">
                <span class="eyebrow"><?= htmlspecialchars((string) ($offer['label'] ?? 'Special')) ?></span>
                <h2><?= htmlspecialchars((string) $offer['title']) ?></h2>
                <p><?= htmlspecialchars((string) $offer['description']) ?></p>
                <p><strong>Valid:</strong> <?= htmlspecialchars((string) $offer['validity']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php render_footer($site); ?>
