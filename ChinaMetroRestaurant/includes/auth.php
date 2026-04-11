<?php

declare(strict_types=1);

require_once __DIR__ . '/data.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    if (PHP_SAPI === 'cli') {
        $_SESSION ??= [];
    } else {
        session_start();
    }
}

function current_admin(): ?array
{
    return $_SESSION['admin'] ?? null;
}

function require_admin(): void
{
    if (!current_admin()) {
        redirect_to('/ChinaMetroRestaurant/admin/login.php');
    }
}

function attempt_login(string $username, string $password): bool
{
    $admin = get_admin_by_username($username);

    if ($admin === null) {
        return false;
    }

    if (!password_verify($password, (string) ($admin['password_hash'] ?? ''))) {
        return false;
    }

    $_SESSION['admin'] = [
        'id' => (int) $admin['id'],
        'username' => $admin['username'],
        'name' => $admin['name'] ?? $admin['username'],
    ];

    return true;
}

function logout_admin(): void
{
    unset($_SESSION['admin']);
}

function current_customer(): ?array
{
    return $_SESSION['customer'] ?? null;
}

function refresh_customer_session(int $userId): void
{
    $user = get_user_by_id($userId);

    if ($user === null) {
        unset($_SESSION['customer']);
        return;
    }

    $_SESSION['customer'] = [
        'id' => (int) $user['id'],
        'full_name' => $user['full_name'],
        'email' => $user['email'],
        'phone' => $user['phone'],
        'created_at' => $user['created_at'],
    ];
}

function require_customer(): void
{
    if (!current_customer()) {
        redirect_to('/ChinaMetroRestaurant/login.php');
    }
}

function attempt_customer_login(string $email, string $password): bool
{
    $user = get_user_by_email($email);

    if ($user === null) {
        return false;
    }

    if (!password_verify($password, (string) ($user['password_hash'] ?? ''))) {
        return false;
    }

    refresh_customer_session((int) $user['id']);
    return true;
}

function logout_customer(): void
{
    unset($_SESSION['customer']);
}
