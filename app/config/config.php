<?php

declare(strict_types = 1);

use app\config\DotEnv;


// project root directory
define('ROOT_DIR', dirname(__FILE__, 3));

(new DotEnv(ROOT_DIR.'/.env'))->load();

// database credentials
define('PHPMYADMIN_CONFIG', [
    'host' => getenv('PHPMYADMIN_HOST'),
    'base' => getenv('PHPMYADMIN_NAME'),
    'user' => getenv('PHPMYADMIN_USER'),
    'pass' => getenv('PHPMYADMIN_PASS')
]);
define('POSTGRESQL_CONFIG', [
    'host' => getenv('POSTGRESQL_HOST'),
    'port' => getenv('POSTGRESQL_PORT'),
    'base' => getenv('POSTGRESQL_NAME'),
    'user' => getenv('POSTGRESQL_USER'),
    'pass' => getenv('POSTGRESQL_PASS')
]);

// SMTP server credentials
define('SMTP_CONFIG', [
    'pass' => getenv('SMTP_PASS')
]);