<?php
// Generated on 2023-04-05 14:12:27

declare(strict_types = 1);

namespace migrations;

use src\models\database\PhpMyAdmin;


class m_0003 extends PhpMyAdmin
{
    public function __construct()
    {
        parent::__construct();
    }

    public function operations()
    {
        $this->connection->exec(
            "ALTER TABLE Token ADD COLUMN expire timestamp NOT NULL;
			
        ");
    }

    public function register()
    {
        $this->connection->exec("INSERT INTO Migrations (name, applied) VALUES ('m_0003', now())");
    }
}