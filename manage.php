<?php

declare(strict_types = 1);

require_once "vendor/autoload.php";
require_once "app/config/config.php";

use app\Command;
use app\tools\Email;
use app\tools\Migrations;


$app = new Command(array_slice($argv, 1));

$app->register(new Email(), new Migrations());

$app->run();