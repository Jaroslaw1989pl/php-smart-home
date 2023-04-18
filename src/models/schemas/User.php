<?php

declare(strict_types = 1);

namespace src\models\schemas;

use src\models\database\Model;


class User extends Model
{
    public array $uuid;
    public array $first_name;
    public array $last_name;
    public array $email;
    public array $email_update;
    public array $pass;
    public array $pass_update;

    public function __construct()
    {
        parent::__construct();

        $this->uuid         = self::varchar(length: 32, nullable: false);
        $this->first_name   = self::varchar(length: 32);
        $this->last_name    = self::varchar(length: 32);
        $this->email        = self::varchar(length: 32, nullable: false);
        $this->email_update = self::timestamp(nullable: false);
        $this->pass         = self::varchar(length: 255, nullable: false);
        $this->pass_update  = self::timestamp(nullable: false);
    }
}