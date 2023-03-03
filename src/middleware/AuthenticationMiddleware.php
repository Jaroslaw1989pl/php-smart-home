<?php

declare(strict_types = 1);

namespace src\middleware;

use app\Request;
use app\Response;
use app\Session;
use models\TokenModel;


class AuthenticationMiddleware
{
    public static function user(): void
    {
        if (!Session::getUserId())
            Response::redirect('/login');
    }
    
    public static function token(): void
    {
        try
        {
            $token = Request::body()['q'];

            if (!$token)
                throw new \Exception('Invalid token authentication.');
            if (!TokenModel::verify($token) && !Session::getRequestStatus())
                throw new \Exception('Invalid token authentication.');
        }
        catch (\Exception $exception)
        {
            Response::redirect('/password-reset');
        }
    }
}
