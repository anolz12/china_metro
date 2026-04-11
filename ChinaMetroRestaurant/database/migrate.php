<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/config.php';

function load_json(string $path): array
{
    if (!is_file($path)) {
        return [];
    }

    $contents = file_get_contents($path);
    if ($contents === false) {
        throw new RuntimeException('Unable to read ' . $path);
    }

    $decoded = json_decode($contents, true);
    if (!is_array($decoded)) {
        throw new RuntimeException('Invalid JSON in ' . $path);
    }

    return $decoded;
}

function run_sql_batch(PDO $pdo, string $sql): void
{
    foreach (explode(";
", $sql) as $statement) {
        $trimmed = trim($statement);
        if ($trimmed === '') {
            continue;
        }
        $pdo->exec($trimmed);
    }
}

$serverDsn = sprintf('mysql:host=%s;port=%d;charset=%s', DB_HOST, DB_PORT, DB_CHARSET);
$serverPdo = new PDO($serverDsn, DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

$schemaSql = file_get_contents(__DIR__ . '/schema.sql');
if ($schemaSql === false) {
    throw new RuntimeException('Unable to read schema.sql');
}
run_sql_batch($serverPdo, $schemaSql);

db_seed();

echo "Migration completed successfully." . PHP_EOL;

function db_seed(): void
{
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', DB_HOST, DB_PORT, DB_NAME, DB_CHARSET);
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $root = dirname(__DIR__);
    $site = load_json($root . '/data/site.json');
    $admins = load_json($root . '/data/admins.json');
    $users = load_json($root . '/data/users.json');
    $menu = load_json($root . '/data/menu.json');
    $offers = load_json($root . '/data/offers.json');
    $contacts = load_json($root . '/data/contacts.json');

    $pdo->beginTransaction();

    try {
        $pdo->exec('DELETE FROM contacts');
        $pdo->exec('DELETE FROM offers');
        $pdo->exec('DELETE FROM menu_items');
        $pdo->exec('DELETE FROM users');
        $pdo->exec('DELETE FROM admins');
        $pdo->exec('DELETE FROM site_settings');

        $site = array_merge([
            'name' => 'China Metro Restaurant',
            'tagline' => '',
            'hero_title' => '',
            'hero_text' => '',
            'about' => '',
            'address' => '',
            'phone' => '',
            'email' => '',
            'hours' => '',
            'footer_blurb' => '',
        ], $site);

        $siteStmt = $pdo->prepare('INSERT INTO site_settings (id, name, tagline, hero_title, hero_text, about, address, phone, email, hours, footer_blurb) VALUES (1, :name, :tagline, :hero_title, :hero_text, :about, :address, :phone, :email, :hours, :footer_blurb)');
        $siteStmt->execute($site);

        $adminStmt = $pdo->prepare('INSERT INTO admins (id, username, name, password_hash) VALUES (:id, :username, :name, :password_hash)');
        foreach ($admins as $index => $admin) {
            $adminStmt->execute([
                'id' => (int) ($admin['id'] ?? ($index + 1)),
                'username' => (string) ($admin['username'] ?? 'admin'),
                'name' => (string) ($admin['name'] ?? 'Admin'),
                'password_hash' => (string) ($admin['password_hash'] ?? ''),
            ]);
        }

        $userStmt = $pdo->prepare('INSERT INTO users (id, full_name, email, phone, password_hash) VALUES (:id, :full_name, :email, :phone, :password_hash)');
        foreach ($users as $index => $user) {
            $userStmt->execute([
                'id' => (int) ($user['id'] ?? ($index + 1)),
                'full_name' => (string) ($user['full_name'] ?? ''),
                'email' => strtolower((string) ($user['email'] ?? '')),
                'phone' => (string) ($user['phone'] ?? ''),
                'password_hash' => (string) ($user['password_hash'] ?? ''),
            ]);
        }

        $menuStmt = $pdo->prepare('INSERT INTO menu_items (id, item_number, name, name_ar, category, category_ar, price, description, featured, sort_order) VALUES (:id, :item_number, :name, :name_ar, :category, :category_ar, :price, :description, :featured, :sort_order)');
        foreach ($menu as $index => $item) {
            $menuStmt->execute([
                'id' => (int) ($item['id'] ?? ($index + 1)),
                'item_number' => (string) ($item['number'] ?? ''),
                'name' => (string) ($item['name'] ?? ''),
                'name_ar' => (string) ($item['name_ar'] ?? ''),
                'category' => (string) ($item['category'] ?? 'Chef Specials'),
                'category_ar' => (string) ($item['category_ar'] ?? ''),
                'price' => (float) ($item['price'] ?? 0),
                'description' => (string) ($item['description'] ?? ''),
                'featured' => !empty($item['featured']) ? 1 : 0,
                'sort_order' => $index + 1,
            ]);
        }

        $offerStmt = $pdo->prepare('INSERT INTO offers (id, title, label, description, validity, is_active, sort_order) VALUES (:id, :title, :label, :description, :validity, :is_active, :sort_order)');
        foreach ($offers as $index => $offer) {
            $offerStmt->execute([
                'id' => (int) ($offer['id'] ?? ($index + 1)),
                'title' => (string) ($offer['title'] ?? ''),
                'label' => (string) ($offer['label'] ?? ''),
                'description' => (string) ($offer['description'] ?? ''),
                'validity' => (string) ($offer['validity'] ?? ''),
                'is_active' => !empty($offer['active']) ? 1 : 0,
                'sort_order' => $index + 1,
            ]);
        }

        $contactStmt = $pdo->prepare('INSERT INTO contacts (id, name, phone, email, note, submitted_at) VALUES (:id, :name, :phone, :email, :note, :submitted_at)');
        foreach ($contacts as $index => $contact) {
            $contactStmt->execute([
                'id' => (int) ($contact['id'] ?? ($index + 1)),
                'name' => (string) ($contact['name'] ?? ''),
                'phone' => (string) ($contact['phone'] ?? ''),
                'email' => (string) ($contact['email'] ?? ''),
                'note' => (string) ($contact['note'] ?? ''),
                'submitted_at' => (string) ($contact['submitted_at'] ?? date('Y-m-d H:i:s')),
            ]);
        }

        $pdo->commit();
    } catch (Throwable $exception) {
        $pdo->rollBack();
        throw $exception;
    }
}
