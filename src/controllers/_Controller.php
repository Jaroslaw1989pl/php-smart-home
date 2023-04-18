<?php

declare(strict_types = 1);

namespace src\controllers;

use app\Session;
// require_once ROOT_DIR.'/app/attributes/Middleware.php';
// require_once ROOT_DIR.'/app/attributes/Route.php';
use src\models\schemas\User;
// use src\models\UserModel;


class _Controller
{
    protected User      $user;
    // protected UserModel $userModel;
    protected array     $data;

    public function __construct()
    {
        // $this->userModel = new UserModel();
        $this->user = new User();

        $this->data = [
            "title"          => "change title",
            "requestSuccess" => Session::getRequestStatus(),
            "inputs"         => Session::getInputs(),
            "errors"         => Session::getErrors(),
            "flash"          => Session::getFlashMessage()
        ];

        if ($userData = $this->user->get(["uuid" => Session::getUserId()]))
        {
            list("uuid" => $uuid, "email" => $email, "email_update" => $emailUpdate, "pass_update" => $passUpdate) = $userData;
            
            $this->data['user'] = ["uuid" => $uuid, "email" => $email, "emailUpdate" => $emailUpdate, "passUpdate" => $passUpdate];
        }
    }

    protected function render(string $layout = 'main', string $view = 'home', array $data = []): void
    {
        ob_start();
        include_once ROOT_DIR."/public/layouts/$layout.php";
        $layoutContent = ob_get_clean();

        ob_start();
        include_once ROOT_DIR."/public/views/$view.php";
        $viewContent = ob_get_clean();

        echo str_replace('{{content}}', $viewContent, $layoutContent);
    }
}