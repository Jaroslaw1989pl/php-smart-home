<?php

declare(strict_types = 1);

namespace src\controllers;

use app\Session;
// require_once ROOT_DIR.'/app/attributes/Middleware.php';
// require_once ROOT_DIR.'/app/attributes/Route.php';
use models\UserModel;


class _Controller
{
    protected UserModel $userModel;
    protected array $data;

    public function __construct()
    {
        $this->userModel = new UserModel();

        $this->data = [
            'title' => 'change title',
            'requestSuccess' => Session::getRequestStatus(),
            'inputs' => Session::getInputs(),
            'errors' => Session::getErrors(),
            'flash' => Session::getFlashMessage(),
            'user' => $this->userModel->get(Session::getUserId())
        ];
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