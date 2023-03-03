<?php

declare(strict_types = 1);

namespace app;


class Session
{
    public function __construct()
    {
        session_start();
    }

    public static function clear(): void
    {
        session_unset();
        session_destroy();
    }

    public static function setRequestStatus(bool $value): void
    {
        $_SESSION['requestStatus'] = $value;
    }

    public static function getRequestStatus(): bool|null
    {
        return $_SESSION['requestStatus'];
    }

    public static function unsetRequestStatus(): void
    {
        unset($_SESSION['requestStatus']);
    }

    public static function setInputs(mixed $value): void
    {
        $_SESSION['inputs'] = $value;
    }

    public static function getInputs(): mixed
    {
        return $_SESSION['inputs'];
    }

    public static function unsetInputs(): void
    {
        unset($_SESSION['inputs']);
    }

    public static function setErrors(mixed $value): void
    {
        $_SESSION['errors'] = $value;
    }

    public static function getErrors(): mixed
    {
        return $_SESSION['errors'];
    }

    public static function unsetErrors(): void
    {
        unset($_SESSION['errors']);
    }

    public static function setUserId(string|int $value): void
    {
        $_SESSION['user'] = $value;
    }

    public static function getUserId(): string|int|null
    {
        return $_SESSION['user'];
    }

    public static function unsetUserId(): void
    {
        unset($_SESSION['user']);
    }

    public static function setFlashMessage(string $message, string $type = 'success'|'error'): void
    {
        $_SESSION['flash'][] = ['message' => $message, 'type' => $type];
    }

    public static function getFlashMessage(): ?array
    {
        return $_SESSION['flash'];
    }

    public static function unsetFlashMessage(): void
    {
        unset($_SESSION['flash']);
    }
}