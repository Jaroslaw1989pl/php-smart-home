<?php

declare(strict_types = 1);

namespace src\models\schemas;

use src\models\database\Model;


class Profile extends Model
{
    public array $uuid;

    public function __construct()
    {
        parent::__construct();

        $this->uuid = self::varchar(length: 32, nullable: false);
    }
}