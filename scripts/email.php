<?php

declare(strict_types = 1);

require_once '../vendor/autoload.php';
require_once '../app/config/config.php';

use models\EmailModel;


$email = $argv[1];
$token = $argv[2];

$subject = 'Password reset';
$message = '
    <html lang="en">
        <head><title>Password reset</title></head>
        <body>
            <p>Password reset: <a href="http://smart-home.pl/password-update?q='.$token.'">UNSUB</a></p>
        </body>
    </html>';

(new EmailModel())->address($email)->message($subject, $message)->send();

exit();