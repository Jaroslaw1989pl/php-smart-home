<?php

declare(strict_types = 1);

namespace app;


class Response
{
    public static function statusCode(int $code): void
    {
        http_response_code($code);
    }
    private static function setMimeType(): string
    {
        return match (strtolower(pathinfo(Request::path(), PATHINFO_EXTENSION))) {
            'html' => 'text/html',
            'json' => 'application/json',
            'js' => 'text/javascript',
            'css' => 'text/css',
            'png', 'ico' => 'image/png',
            'jpg' => 'image/jpg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'svg' => 'image/svg+xml',
            'wav' => 'audio/wav',
            'mp4' => 'video/mp4',
            'ttf' => 'application/font-ttf',
            default => 'text/plain',
        };
    }

    public static function responseHeaders(): void
    {
        header_remove();
        header('Content-Type: '.self::setMimeType());
    }

    public static function redirect(?string $path = null): void
    {
        header_remove();
        header('Location: ' . ($path ?? $_SERVER['HTTP_REFERER']));
        exit();
    }
}