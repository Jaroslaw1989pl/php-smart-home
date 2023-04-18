<?php

declare(strict_types = 1);

namespace app;

use app\enumerators\CommandLineEnums as CLE;


class Command
{
    protected ?string $command   = null;
    protected ?string $method    = null;
    protected array   $toolsList = [];
    protected array   $arguments = [];
    private   array   $error     = [];

    public function __construct(array $arguments)
    {
        if (!empty($arguments))
        {
            @list($command, $method) = explode(':', $arguments[0]);
            
            if ($command)
                $this->command = $command;
            if ($method)
                $this->method = $method;
            if (array_slice($arguments, 1))
                $this->arguments = array_slice($arguments, 1);
        }
    }

    public function __destruct()
    {
        if (!empty($this->error))
        {
            echo CLE::Red->value.$this->error['message'].CLE::End->value.PHP_EOL;

            if ($this->error['code'] <= 2)
            {
                echo CLE::Green->value."ALL AVAILABLE ".CLE::Bold->value."COMMANDS:".CLE::End->value.PHP_EOL;
                foreach ($this->toolsList as $tool)
                    echo "$tool";
            }
            else if ($this->error['code'] <= 4)
            {
                echo CLE::Green->value."ALL AVAILABLE ".CLE::Bold->value."METHODS:".CLE::End->value.PHP_EOL;
                echo $this->toolsList["app\\tools\\".ucfirst($this->command)];
            }
        }
        exit();
    }

    public function register(object ...$classList): void
    {
        foreach ($classList as $object)
            $this->toolsList[get_class($object)] = $object;
    }

    public function run(): void
    {
        try {
            if (!$this->command)
                throw new \Exception("No command", 1);
            if (!isset($this->toolsList["app\\tools\\".ucfirst($this->command)]))
                throw new \Exception("Incorrect command", 2);
            if (!$this->method)
                throw new \Exception("No method", 3);

            call_user_func_array([$this->toolsList["app\\tools\\".ucfirst($this->command)], $this->method], [$this->arguments]);

        } catch (\Exception $error) {
            $this->error = ['message' => $error->getMessage(), 'code' => $error->getCode()];
        }
    }
}