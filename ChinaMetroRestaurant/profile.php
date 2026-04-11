<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

require_customer();

$site = get_site_content();
$customer = current_customer();
$user = get_user_by_id((int) $customer['id']);

if ($user === null) {
    logout_customer();
    redirect_to('/ChinaMetroRestaurant/login.php');
}

$profileMessage = '';
$profileType = 'success';
$passwordMessage = '';
$passwordType = 'success';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'profile') {
        $fullName = trim((string) ($_POST['full_name'] ?? ''));
        $phone = trim((string) ($_POST['phone'] ?? ''));

        if ($fullName === '') {
            $profileMessage = 'Full Name is required.';
            $profileType = 'error';
        } elseif (update_user_profile((int) $user['id'], $fullName, $phone)) {
            refresh_customer_session((int) $user['id']);
            $user = get_user_by_id((int) $user['id']) ?? $user;
            $profileMessage = 'Profile details updated successfully.';
        } else {
            $profileMessage = 'Profile details could not be updated.';
            $profileType = 'error';
        }
    }

    if ($action === 'password') {
        $currentPassword = (string) ($_POST['current_password'] ?? '');
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmNewPassword = (string) ($_POST['confirm_new_password'] ?? '');

        if ($currentPassword === '' || $newPassword === '' || $confirmNewPassword === '') {
            $passwordMessage = 'All password fields are required.';
            $passwordType = 'error';
        } elseif (!password_verify($currentPassword, (string) $user['password_hash'])) {
            $passwordMessage = 'Current password is incorrect.';
            $passwordType = 'error';
        } elseif (strlen($newPassword) < 8) {
            $passwordMessage = 'New password must be at least 8 characters long.';
            $passwordType = 'error';
        } elseif ($newPassword !== $confirmNewPassword) {
            $passwordMessage = 'New Password and Confirm New Password must match.';
            $passwordType = 'error';
        } elseif (update_user_password((int) $user['id'], $newPassword)) {
            $user = get_user_by_id((int) $user['id']) ?? $user;
            $passwordMessage = 'Password changed successfully.';
        } else {
            $passwordMessage = 'Password could not be updated.';
            $passwordType = 'error';
        }
    }
}

render_header('Profile', $site, 'profile');
?>
<section class="page-hero">
    <span class="eyebrow">My Account</span>
    <h1 class="page-title">Your personal profile</h1>
    <p class="lead">View your account details, update your name and phone number, and change your password securely.</p>
</section>

<section class="section">
    <div class="contact-grid">
        <article class="contact-card">
            <h2>Account details</h2>
            <p><strong>Full Name:</strong> <?= htmlspecialchars((string) $user['full_name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars((string) $user['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars((string) ($user['phone'] ?? '')) ?: 'Not provided' ?></p>
            <p><strong>Join Date:</strong> <?= htmlspecialchars(date('d M Y', strtotime((string) $user['created_at']))) ?></p>
        </article>
        <article class="contact-card">
            <h2>Update profile</h2>
            <?php if ($profileMessage !== ''): ?>
                <div class="message <?= $profileType ?>"><?= htmlspecialchars($profileMessage) ?></div>
            <?php endif; ?>
            <form class="contact-form" method="post">
                <input type="hidden" name="action" value="profile">
                <label>
                    Full Name
                    <input type="text" name="full_name" required value="<?= htmlspecialchars((string) $user['full_name']) ?>">
                </label>
                <label>
                    Email
                    <input type="email" value="<?= htmlspecialchars((string) $user['email']) ?>" readonly>
                </label>
                <label>
                    Phone
                    <input type="text" name="phone" value="<?= htmlspecialchars((string) ($user['phone'] ?? '')) ?>">
                </label>
                <button type="submit">Save Profile</button>
            </form>
        </article>
        <article class="contact-card">
            <h2>Change password</h2>
            <?php if ($passwordMessage !== ''): ?>
                <div class="message <?= $passwordType ?>"><?= htmlspecialchars($passwordMessage) ?></div>
            <?php endif; ?>
            <form class="contact-form" method="post">
                <input type="hidden" name="action" value="password">
                <label>
                    Current Password
                    <input type="password" name="current_password" required>
                </label>
                <label>
                    New Password
                    <input type="password" name="new_password" required minlength="8">
                </label>
                <label>
                    Confirm New Password
                    <input type="password" name="confirm_new_password" required minlength="8">
                </label>
                <button type="submit">Update Password</button>
            </form>
        </article>
    </div>
</section>
<?php render_footer($site); ?>
