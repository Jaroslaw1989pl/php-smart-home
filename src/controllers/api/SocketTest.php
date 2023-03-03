<?php

declare(strict_types = 1);

namespace src\controllers\api;

use app\Request;


class SocketTest extends ApiController
{
    public function saveRequest()
    {
        $headers = [
            'Access-Control-Allow-Origin'   => '*',
            'Access-Control-Allow-Methods'  => 'POST',
            'Access-Control-Allow-Headers'  => 'Content-Type'
        ];
        $value = time();

        // file_put_contents(ROOT_DIR.'/storage/'.$value.'.txt', $value);

        $this->sendResponse(Request::body(), $headers);
    }
}