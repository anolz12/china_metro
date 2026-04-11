<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', DB_HOST, DB_PORT, DB_NAME, DB_CHARSET);
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

function fetch_all(string $sql, array $params = []): array
{
    $statement = db()->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
}

function fetch_one(string $sql, array $params = []): ?array
{
    $statement = db()->prepare($sql);
    $statement->execute($params);
    $row = $statement->fetch();
    return $row === false ? null : $row;
}

function execute_query(string $sql, array $params = []): bool
{
    $statement = db()->prepare($sql);
    return $statement->execute($params);
}

function default_site_content(): array
{
    return [
        'name' => 'China Metro Restaurant',
        'tagline' => 'Chinese and Indo-Chinese favourites served with style',
        'hero_title' => 'A bold restaurant website for a bold dining brand.',
        'hero_text' => 'China Metro Restaurant now has a digital front door where customers can explore the menu, discover offers, learn more about the restaurant, and get in touch with confidence.',
        'about' => 'China Metro Restaurant is designed to showcase flavour, atmosphere, and convenience. The website helps improve visibility, extends customer reach, and strengthens branding while making it easy for guests to find what they need.',
        'address' => 'Farwaniyah, block 6, street 2',
        'phone' => '+965 94156222',
        'email' => 'chinametrorestaurant@gmail.com',
        'hours' => 'Daily: 12:00 PM - 11:00 PM',
        'footer_blurb' => 'Fast access to menu items, current offers, restaurant details, and a cleaner customer experience.',
    ];
}

function get_site_content(): array
{
    $row = fetch_one('SELECT * FROM site_settings WHERE id = 1');

    if ($row === null) {
        return default_site_content();
    }

    unset($row['id'], $row['updated_at']);
    return array_merge(default_site_content(), $row);
}

function save_site_content(array $site): bool
{
    $site = array_merge(default_site_content(), $site);

    return execute_query(
        'UPDATE site_settings SET name = :name, tagline = :tagline, hero_title = :hero_title, hero_text = :hero_text, about = :about, address = :address, phone = :phone, email = :email, hours = :hours, footer_blurb = :footer_blurb WHERE id = 1',
        $site
    );
}

function get_menu_items(): array
{
    $rows = fetch_all('SELECT id, item_number AS number, name, name_ar, category, category_ar, price, description, featured FROM menu_items ORDER BY sort_order ASC, id ASC');

    return array_map(static function (array $row): array {
        $row['id'] = (int) $row['id'];
        $row['price'] = (float) $row['price'];
        $row['featured'] = (bool) $row['featured'];
        $row['number'] = $row['number'] ?? '';
        $row['name_ar'] = $row['name_ar'] ?? '';
        $row['category_ar'] = $row['category_ar'] ?? '';
        $row['description'] = $row['description'] ?? '';
        return $row;
    }, $rows);
}

function save_menu_items(array $items): bool
{
    $pdo = db();
    $pdo->beginTransaction();

    try {
        $pdo->exec('TRUNCATE TABLE menu_items');
        $statement = $pdo->prepare('INSERT INTO menu_items (id, item_number, name, name_ar, category, category_ar, price, description, featured, sort_order) VALUES (:id, :item_number, :name, :name_ar, :category, :category_ar, :price, :description, :featured, :sort_order)');

        foreach ($items as $index => $item) {
            $statement->execute([
                'id' => (int) ($item['id'] ?? ($index + 1)),
                'item_number' => trim((string) ($item['number'] ?? '')),
                'name' => trim((string) ($item['name'] ?? '')),
                'name_ar' => trim((string) ($item['name_ar'] ?? '')),
                'category' => trim((string) ($item['category'] ?? 'Chef Specials')),
                'category_ar' => trim((string) ($item['category_ar'] ?? '')),
                'price' => (float) ($item['price'] ?? 0),
                'description' => trim((string) ($item['description'] ?? '')),
                'featured' => !empty($item['featured']) ? 1 : 0,
                'sort_order' => $index + 1,
            ]);
        }

        $pdo->commit();
        return true;
    } catch (Throwable $exception) {
        $pdo->rollBack();
        return false;
    }
}

function get_offers(): array
{
    $rows = fetch_all('SELECT id, title, label, description, validity, is_active AS active FROM offers ORDER BY sort_order ASC, id ASC');

    return array_map(static function (array $row): array {
        $row['id'] = (int) $row['id'];
        $row['active'] = (bool) $row['active'];
        return $row;
    }, $rows);
}

function save_offers(array $offers): bool
{
    $pdo = db();
    $pdo->beginTransaction();

    try {
        $pdo->exec('TRUNCATE TABLE offers');
        $statement = $pdo->prepare('INSERT INTO offers (id, title, label, description, validity, is_active, sort_order) VALUES (:id, :title, :label, :description, :validity, :is_active, :sort_order)');

        foreach ($offers as $index => $offer) {
            $statement->execute([
                'id' => (int) ($offer['id'] ?? ($index + 1)),
                'title' => trim((string) ($offer['title'] ?? '')),
                'label' => trim((string) ($offer['label'] ?? '')),
                'description' => trim((string) ($offer['description'] ?? '')),
                'validity' => trim((string) ($offer['validity'] ?? '')),
                'is_active' => !empty($offer['active']) ? 1 : 0,
                'sort_order' => $index + 1,
            ]);
        }

        $pdo->commit();
        return true;
    } catch (Throwable $exception) {
        $pdo->rollBack();
        return false;
    }
}

function get_contacts(): array
{
    $rows = fetch_all('SELECT id, name, phone, email, note, submitted_at FROM contacts ORDER BY submitted_at DESC, id DESC');

    return array_map(static function (array $row): array {
        $row['id'] = (int) $row['id'];
        return $row;
    }, $rows);
}

function save_contact(array $contact): bool
{
    return execute_query(
        'INSERT INTO contacts (name, phone, email, note, submitted_at) VALUES (:name, :phone, :email, :note, :submitted_at)',
        [
            'name' => trim((string) ($contact['name'] ?? '')),
            'phone' => trim((string) ($contact['phone'] ?? '')),
            'email' => trim((string) ($contact['email'] ?? '')),
            'note' => trim((string) ($contact['note'] ?? '')),
            'submitted_at' => (string) ($contact['submitted_at'] ?? date('Y-m-d H:i:s')),
        ]
    );
}

function get_admin_users(): array
{
    return fetch_all('SELECT id, username, name, password_hash FROM admins ORDER BY id ASC');
}

function get_admin_by_username(string $username): ?array
{
    return fetch_one('SELECT id, username, name, password_hash FROM admins WHERE username = :username LIMIT 1', [
        'username' => $username,
    ]);
}

function normalize_user_row(array $row): array
{
    $row['id'] = (int) $row['id'];
    $row['phone'] = $row['phone'] ?? '';
    return $row;
}

function get_user_by_email(string $email): ?array
{
    $row = fetch_one('SELECT id, full_name, email, phone, password_hash, created_at FROM users WHERE email = :email LIMIT 1', [
        'email' => strtolower(trim($email)),
    ]);

    return $row === null ? null : normalize_user_row($row);
}

function get_user_by_id(int $id): ?array
{
    $row = fetch_one('SELECT id, full_name, email, phone, password_hash, created_at FROM users WHERE id = :id LIMIT 1', [
        'id' => $id,
    ]);

    return $row === null ? null : normalize_user_row($row);
}

function create_user_account(string $fullName, string $email, string $password): bool
{
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    return execute_query(
        'INSERT INTO users (full_name, email, password_hash) VALUES (:full_name, :email, :password_hash)',
        [
            'full_name' => trim($fullName),
            'email' => strtolower(trim($email)),
            'password_hash' => $hashedPassword,
        ]
    );
}

function update_user_profile(int $id, string $fullName, string $phone): bool
{
    return execute_query(
        'UPDATE users SET full_name = :full_name, phone = :phone WHERE id = :id',
        [
            'id' => $id,
            'full_name' => trim($fullName),
            'phone' => trim($phone),
        ]
    );
}

function update_user_password(int $id, string $newPassword): bool
{
    return execute_query(
        'UPDATE users SET password_hash = :password_hash WHERE id = :id',
        [
            'id' => $id,
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]
    );
}

function menu_categories(array $items): array
{
    $grouped = [];

    foreach ($items as $item) {
        $category = trim((string) ($item['category'] ?? 'Chef Specials'));
        if ($category === '') {
            $category = 'Chef Specials';
        }

        $grouped[$category][] = $item;
    }

    ksort($grouped);
    return $grouped;
}

function featured_menu_items(array $items, int $limit = 8): array
{
    $featured = array_filter(
        $items,
        static fn(array $item): bool => !empty($item['featured'])
    );

    if (count($featured) < $limit) {
        return array_slice($items, 0, $limit);
    }

    return array_slice(array_values($featured), 0, $limit);
}

function currency_format(float $amount): string
{
    return 'KWD ' . number_format($amount, 2);
}

function redirect_to(string $path): never
{
    header('Location: ' . $path);
    exit;
}
