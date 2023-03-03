<?php

declare(strict_types = 1);

require_once __DIR__.'/../vendor/autoload.php';

use app\Application;


$app = new Application();

$app->router->statics();

// routes
require_once ROOT_DIR.'/src/router/routes.php';

$app->router->run();