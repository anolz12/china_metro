<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$site = get_site_content();
$menuItems = get_menu_items();
$offers = array_values(array_filter(get_offers(), static fn(array $offer): bool => !empty($offer['active'])));
$featured = featured_menu_items($menuItems, 6);

render_header('Home', $site, 'home');
?>
<section class="hero">
    <div class="hero-copy">
        <span class="eyebrow">China Metro Signature Experience</span>
        <h1><?= htmlspecialchars((string) ($site['hero_title'] ?? 'Flavour that moves the city.')) ?></h1>
        <p class="lead"><?= htmlspecialchars((string) ($site['hero_text'] ?? 'Discover bold Chinese and Indo-Chinese dishes, quick access to the menu, and a polished dining experience that keeps your brand front and center.')) ?></p>
        <div class="cta-row">
            <a class="button" href="<?= asset_url('menu.php') ?>">Explore Menu</a>
            <a class="button-secondary" href="<?= asset_url('contact.php') ?>">Reserve or Enquire</a>
        </div>
    </div>
    <aside class="hero-card">
        <img src="<?= asset_url('assets/images/logo.jpg') ?>" alt="China Metro Restaurant featured logo">
        <div class="stat-grid">
            <div class="stat">
                <strong><?= count($menuItems) ?>+</strong>
                <span>Menu items ready to browse</span>
            </div>
            <div class="stat">
                <strong><?= count($offers) ?></strong>
                <span>Active offers and promotions</span>
            </div>
            <div class="stat">
                <strong>Local</strong>
                <span>Fast XAMPP-based management</span>
            </div>
        </div>
    </aside>
</section>

<section class="section">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Why This Site Helps</span>
            <h2>Built for visibility, reach, and stronger branding</h2>
        </div>
        <p>Customers get the information they need fast, and your team gets a practical admin area to keep the website fresh.</p>
    </div>
    <div class="feature-grid">
        <article class="panel">
            <h3>Clear Menu Access</h3>
            <p>Guests can quickly browse categories, prices, and descriptions before they visit or call.</p>
        </article>
        <article class="panel">
            <h3>Offer-Driven Growth</h3>
            <p>Promotions are highlighted prominently to improve repeat visits and encourage discovery.</p>
        </article>
        <article class="panel">
            <h3>Easy Brand Presence</h3>
            <p>The site uses your logo and a bold visual identity to make China Metro feel memorable online.</p>
        </article>
    </div>
</section>

<section class="section">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Popular Picks</span>
            <h2>Featured dishes from the menu</h2>
        </div>
        <a class="button-secondary" href="<?= asset_url('menu.php') ?>">View Full Menu</a>
    </div>
    <div class="menu-grid">
        <?php foreach ($featured as $item): ?>
            <article class="menu-card">
                <div class="menu-card-top">
                    <div>
                        <span class="eyebrow"><?= htmlspecialchars((string) $item['category']) ?></span>
                        <h3><?= htmlspecialchars((string) $item['name']) ?></h3>
                    </div>
                    <span class="price"><?= currency_format((float) $item['price']) ?></span>
                </div>
                <p><?= htmlspecialchars((string) $item['description']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Current Offers</span>
            <h2>Special deals worth spotlighting</h2>
        </div>
    </div>
    <div class="offer-grid">
        <?php foreach (array_slice($offers, 0, 3) as $index => $offer): ?>
            <article class="offer-card <?= $index === 0 ? 'featured' : '' ?>">
                <span class="eyebrow"><?= htmlspecialchars((string) ($offer['label'] ?? 'Featured')) ?></span>
                <h3><?= htmlspecialchars((string) $offer['title']) ?></h3>
                <p><?= htmlspecialchars((string) $offer['description']) ?></p>
                <p><strong>Valid:</strong> <?= htmlspecialchars((string) $offer['validity']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Restaurant Information</span>
            <h2>Everything guests usually look for</h2>
        </div>
    </div>
    <div class="info-grid">
        <article class="panel">
            <h3>About China Metro</h3>
            <p><?= htmlspecialchars((string) ($site['about'] ?? 'China Metro Restaurant serves modern Chinese and Indo-Chinese cuisine in a welcoming setting.')) ?></p>
        </article>
        <article class="panel">
            <h3>Hours</h3>
            <p><?= nl2br(htmlspecialchars((string) ($site['hours'] ?? 'Daily: 12:00 PM - 11:00 PM'))) ?></p>
        </article>
        <article class="panel">
            <h3>Contact</h3>
            <p><?= htmlspecialchars((string) ($site['phone'] ?? '')) ?><br><?= htmlspecialchars((string) ($site['email'] ?? '')) ?></p>
        </article>
    </div>
</section>
<?php render_footer($site); ?>
