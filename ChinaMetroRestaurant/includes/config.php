<?php

declare(strict_types=1);

define('DB_HOST', getenv('CHINA_METRO_DB_HOST') ?: '127.0.0.1');
define('DB_PORT', (int) (getenv('CHINA_METRO_DB_PORT') ?: '3306'));
define('DB_NAME', getenv('CHINA_METRO_DB_NAME') ?: 'china_metro_restaurant');
define('DB_USER', getenv('CHINA_METRO_DB_USER') ?: 'root');
define('DB_PASS', getenv('CHINA_METRO_DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');
