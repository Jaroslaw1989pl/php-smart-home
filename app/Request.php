<?php

declare(strict_types = 1);

namespace app;


class Request
{
    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    // example: 'http://localhost:8080/default.htm?year=2017&month=february';

    // return: '/default.html?year=2017&month=february'
    public static function fullPath(): string
    {
        return urldecode($_SERVER['REQUEST_URI']);
    }

    // return: '/default.html'
    public static function path(): string
    {
        return $_SERVER['REDIRECT_URL'];
    }

    // return: '?year=2017&month=february'
    public static function query(): string
    {
        return urldecode($_SERVER['QUERY_STRING']);
    }

    // return array [ year: 2017, month: 'february' ]
    public static function body(): array
    {
        $body = [];

        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            foreach ($_GET as $key => $value)
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }
        else if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            foreach ($_POST as $key => $value)
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            foreach ($_FILES as $key => $value)
                $body[$key] = $value;
        }
        else
        {
            $data = file_get_contents('php://input');

            foreach (explode('&', $data) as $set)
            {
                list($key, $value) = explode('=', $set);
                $body[$key] = $value;
            }
        }

        return $body;
    }

    // public static function setBody(array $data): void
    // {
    //     if ($data) $_GET = $data;
    // }
}