<?php

declare(strict_types = 1);

namespace src\models\schemas;

use src\models\database\Model;


class Token extends Model
{
    public array $email;
    public array $token;
    public array $expire;

    public function __construct()
    {
        parent::__construct();

        $this->email  = self::varchar(length: 32, unique: true, nullable: false);
        $this->token  = self::varchar(length: 255, nullable: false);
        $this->expire = self::timestamp(nullable: false);
    }

    /**
     * @throws \Exception
     */
    public function create(): string
    {
        return bin2hex(random_bytes(32));
    }

    public function add(array $data): void
    {
        $columns = array_keys($data);
        $query   = "INSERT INTO Token (%s) VALUES (%s) ON DUPLICATE KEY UPDATE token = :token, expire = :expire";
        $query   = sprintf($query, join(", ", $columns), join(", ", array_map(fn ($key) => ":".$key, $columns)));

        $this->insert($query, $data);
    }

    public function verify(array $data): bool
    {
        $result = $this->get($data);

        if (empty($result)) return false;
        else if (strtotime($result['expire']) < strtotime("now"))
        {
            $this->remove($data);
            return false;
        }
        
        return true;
    }
}