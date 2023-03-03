<?php

declare(strict_types = 1);

namespace src\controllers;

use app\Request;
use app\Session;


class ViewController extends _Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        Session::unsetRequestStatus();
        Session::unsetInputs();
        Session::unsetErrors();
        Session::unsetFlashMessage();
    }

    /********* PUBLIC VIEWS *********/

    // #[Get(path: '/')]
    public function home(): void
    {
        $this->data['title'] = 'Home page';

        $this->render('main', 'home', $this->data);
    }

    // #[Get(path: '/registration')]
    public function registration(): void
    {
        $this->data['title'] = 'Join us';

        $this->render('auth', 'auth-registration', $this->data);
    }

    // #[Get(path: '/login')]
    public function login(): void
    {
        $this->data['title'] = 'Sign in';

        $this->render('auth', 'auth-login', $this->data);
    }

    // #[Get(path: '/password-reset')]
    public function passwordReset(): void
    {
        $this->data['title'] = 'Password reset';

        $this->render('auth', 'auth-password-reset', $this->data);
    }

    // #[Middleware(['authenticateToken'])]
    // #[Get(path: '/password-update')]
    public function passwordUpdate(): void
    {
        $this->data['title'] = 'Password reset';
        $this->data['inputs']['token'] = Request::body()['q'];

        $this->render('auth', 'auth-password-update', $this->data);
    }

    /********* PROTECTED VIEWS *********/

    // #[Middleware(['AuthMiddleware::user'])]
    // #[Get(path: '/user-panel/profile')]
    // #[Get(path: '/user-panel/settings')]
    public function userPanel(): void
    {
        $this->data['title'] = 'User panel';

        $this->render('main', 'user-panel', $this->data);
    }

    /*********** ERROR VIEWS ***********/

    public function notFound(): void
    {
        $this->data['title'] = '404 Not found';

        $this->render('error', '404-not-found', $this->data);
    }
}