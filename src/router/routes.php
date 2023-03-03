<?php

declare(strict_types = 1);

use src\router\Router;
// controllers
use src\controllers\ViewController;
use src\controllers\AuthController;
use src\controllers\UserController;
// api controllers
use src\controllers\api\SocketTest;
// middleware
$authMiddleware = 'src\middleware\AuthenticationMiddleware';

// Router::attributeRouting([
//     ViewController::class,
//     AuthenticationController::class,
//     UserController::class,
//     PostController::class,
//     LocationController::class
// ]);

/********** public routes **********/
Router::route(path: '/')
    ->get(middleware: [], action: [ViewController::class, 'home']);

/********** authentication routes **********/
Router::route(path: '/registration')
    ->get(middleware: [], action: [ViewController::class, 'registration'])
    ->post(middleware: [], action: [AuthController::class, 'registration']);
Router::route(path: '/login')
    ->get(middleware: [], action: [ViewController::class, 'login'])
    ->post(middleware: [], action: [AuthController::class, 'login']);
Router::route(path: '/password-reset')
    ->get(middleware: [], action: [ViewController::class, 'passwordReset'])
    ->post(middleware: [], action: [AuthController::class, 'authenticationEmail']);
Router::route(path: '/password-update')
    ->get(middleware: ["$authMiddleware::token"], action: [ViewController::class, 'passwordUpdate'])
    ->post(middleware: [], action: [AuthController::class, 'passwordUpdate']);
Router::route(path: '/password-new')
    ->post(middleware: [], action: [AuthController::class, 'passwordNew']);
Router::route(path: '/logout')
    ->post(middleware: [], action: [AuthController::class, 'logout']);
Router::route(path: '/delete')
    ->post(middleware: [], action: [AuthController::class, 'delete']);

// /********** protected routes **********/
Router::route(path: '/user-panel/profile')
    ->get(middleware: ["$authMiddleware::user"], action: [ViewController::class, 'userPanel'])
    ->post(middleware: [], action: [UserController::class, 'profile']);
Router::route(path: '/user-panel/settings')
    ->get(middleware: ["$authMiddleware::user"], action: [ViewController::class, 'userPanel']);
// Router::route(path: '/user-panel/avatar')
//     ->post(middleware: [], action: ['UserController', 'avatar']);

/********** api routes **********/
Router::route(path: '/socket-test')
    ->post(middleware: [], action: [SocketTest::class, 'saveRequest']);

/********** error routes **********/
Router::not_found(ViewController::class, 'notFound');