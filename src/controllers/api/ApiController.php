<?php

declare(strict_types = 1);

namespace src\controllers\api;


class ApiController
{
    protected function sendResponse($data, array $httpHeaders = [], int $statusCode = 200): void
    {
        header_remove();
        http_response_code($statusCode);

        if (count($httpHeaders))
            foreach ($httpHeaders as $key => $value) header($key.': '.$value);

        echo json_encode($data);

        exit();
    }
}