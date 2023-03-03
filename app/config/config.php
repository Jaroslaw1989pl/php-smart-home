<?php

declare(strict_types = 1);

use models\DotEnv;


// project root directory
define('ROOT_DIR', dirname(__FILE__, 3));

(new DotEnv(ROOT_DIR.'/.env'))->load();

// database credentials
define('DB_CONFIG', [
    'host' => getenv('DB_HOST'),
    'port' => getenv('DB_PORT'),
    'base' => getenv('DB_NAME'),
    'user' => getenv('DB_USER'),
    'pass' => getenv('DB_PASS')
]);

// SMTP server credentials
define('SMTP_CONFIG', [
    'pass' => getenv('SMTP_PASS')
]);