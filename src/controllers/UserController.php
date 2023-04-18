<?php

declare(strict_types = 1);

namespace src\controllers;

use app\Request;
use app\Response;
use app\Session;


class UserController extends _Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // #[Post(path: '/user-panel/profile')]
    public function profile()
    {
        $formErrors    = [];
        $userAvatar    = Request::body()['avatar'] ?? null;
        $userFirstName = trim(Request::body()['userFirstName']);
        $userLastName  = trim(Request::body()['userLastName']);
        $userPhone     = Request::body()['userPhone'];
        // $userLocation = trim(Request::body()['userLocation']);

        // echo '<pre>';
        // var_dump(Request::body());
        // exit();

        // setting up user avatar
        if ($userAvatar) $this->userModel->setAvatar($this->data['user']['id'], $userAvatar);

        // setting up user first name
        if (strlen($userFirstName) > 0 && strlen($userFirstName) < 3)
            $formErrors['userFirstName'] = 'First name should be at least 3 characters long.';
        else if (strlen($userFirstName) >= 3)
            $this->userModel->setFirstName($this->data['user']['id'], $userFirstName);

        // setting up user last name
        if (strlen($userLastName) > 0 && strlen($userLastName) < 3)
            $formErrors['userLastName'] = 'Last name should be at least 3 characters long.';
        else if (strlen($userLastName) >= 3)
            $this->userModel->setLastName($this->data['user']['id'], $userLastName);

        // setting up user phone number
        if ($userPhone && (strlen($userPhone) < 6 || preg_match('/[^0-9]/', $userPhone)))
            $formErrors['userPhone'] = 'Incorrect phone number.';
        else if ($userPhone)
            $this->userModel->setPhone($this->data['user']['id'], $userPhone);

        // setting up user location
        // if (strlen($userLocation) > 0 && strlen($userLocation) < 3)
        //     $formErrors['userLocation'] = 'This location is probably incorrect.';
        // else if (strlen($userLocation) >= 3)
        //     $this->userModel->setLocation($this->data['user']['id'], $userLocation);


        Session::setInputs(Request::body());
        Session::setErrors($formErrors);

        Response::redirect();
    }
}