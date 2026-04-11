<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$site = get_site_content();
$message = '';
$type = 'success';
$mapQuery = rawurlencode((string) ($site['address'] ?? 'Farwaniyah, block 6, street 2, Kuwait'));
$mapOpenUrl = 'https://maps.app.goo.gl/TjU5Xuv1W1ESzyQq5';
$mapEmbedUrl = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3480.5171041582057!2d47.9674385!3d29.267142900000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3fcf997b8a0a0d4f%3A0xe0a6457e2e0f4c3b!2sChina%20Metro%20Restaurant!5e0!3m2!1sen!2sin!4v1775903183055!5m2!1sen!2sin';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $name = trim((string) ($_POST['name'] ?? ''));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $note = trim((string) ($_POST['note'] ?? ''));

    if ($name === '' || $phone === '' || $note === '') {
        $message = 'Please fill in your name, phone number, and message.';
        $type = 'error';
    } else {
        if (save_contact([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'note' => $note,
            'submitted_at' => date('Y-m-d H:i:s'),
        ])) {
            $message = 'Your message has been saved. The restaurant team can review it from the admin dashboard.';
            $_POST = [];
        } else {
            $message = 'The message could not be saved. Please try again.';
            $type = 'error';
        }
    }
}

render_header('Contact', $site, 'contact');
?>
<section class="page-hero">
    <span class="eyebrow">Contact</span>
    <h1 class="page-title">Reach China Metro Restaurant</h1>
    <p class="lead">Help guests quickly find your hours, address, and a simple enquiry form without sending them through a complicated process.</p>
</section>

<section class="section">
    <div class="contact-grid">
        <article class="contact-card">
            <h2>Visit or call</h2>
            <p><?= nl2br(htmlspecialchars((string) ($site['address'] ?? ''))) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars((string) ($site['phone'] ?? '')) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars((string) ($site['email'] ?? '')) ?></p>
            <p><strong>Hours:</strong><br><?= nl2br(htmlspecialchars((string) ($site['hours'] ?? ''))) ?></p>
            <a class="button-secondary" href="<?= htmlspecialchars($mapOpenUrl) ?>" target="_blank" rel="noopener noreferrer">Open in Google Maps</a>
        </article>
        <article class="contact-card">
            <h2>Send an enquiry</h2>
            <?php if ($message !== ''): ?>
                <div class="message <?= $type ?>"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
            <form class="contact-form" method="post">
                <label>
                    Name
                    <input type="text" name="name" value="<?= htmlspecialchars((string) ($_POST['name'] ?? '')) ?>">
                </label>
                <label>
                    Phone
                    <input type="text" name="phone" value="<?= htmlspecialchars((string) ($_POST['phone'] ?? '')) ?>">
                </label>
                <label>
                    Email
                    <input type="email" name="email" value="<?= htmlspecialchars((string) ($_POST['email'] ?? '')) ?>">
                </label>
                <label>
                    Message
                    <textarea name="note"><?= htmlspecialchars((string) ($_POST['note'] ?? '')) ?></textarea>
                </label>
                <button type="submit">Send Enquiry</button>
            </form>
        </article>
        <article class="contact-card map-card">
            <div class="map-card-header">
                <div>
                    <span class="eyebrow">Find Us</span>
                    <h2>Farwaniyah, Block-6,Street-2</h2>
                </div>
                <a class="button-secondary" href="<?= htmlspecialchars($mapOpenUrl) ?>" target="_blank" rel="noopener noreferrer">Directions</a>
            </div>
            <div class="map-embed-wrap">
                <iframe
                    class="map-embed"
                    src="<?= htmlspecialchars($mapEmbedUrl) ?>"
                    allowfullscreen
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="China Metro Restaurant location on Google Maps"></iframe>
            </div>
        </article>
    </div>
</section>
<?php render_footer($site); ?>
