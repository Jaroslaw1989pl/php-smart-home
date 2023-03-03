<?php

declare(strict_types = 1);

namespace models;


class DotEnv
{
    protected string $path;

    public function __construct(string $path)
    {
        if (!file_exists($path)) throw new \InvalidArgumentException('.env file does not exist!');
        else $this->path = $path;
    }

    public function load(): void
    {
        if (!is_readable($this->path)) throw new \RuntimeException('.env file is not readable!');

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line)
        {
            if (str_starts_with($line, '#')) continue;

            list($key, $value) = explode('=', $line, 2);

            if (!array_key_exists($key, $_ENV))
            {
                putenv(sprintf('%s=%s', $key, $value));
                $_ENV[$key] = $value;
            }
        }
    }
}