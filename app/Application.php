<?php

declare(strict_types = 1);

namespace app;

require_once 'config/config.php';

use src\router\Router;


class Application
{
    public Router  $router;
    public Session $session;

    public function __construct()
    {
        $this->session = new Session();
        $this->router  = new Router();
    }
}