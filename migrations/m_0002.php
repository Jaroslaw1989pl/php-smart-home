<?php
// Generated on 2023-04-05 12:44:05

declare(strict_types = 1);

namespace migrations;

use src\models\database\PhpMyAdmin;


class m_0002 extends PhpMyAdmin
{
    public function __construct()
    {
        parent::__construct();
    }

    public function operations()
    {
        $this->connection->exec(
            "CREATE TABLE IF NOT EXISTS Token (
				id int NOT NULL AUTO_INCREMENT,
				email varchar(32) UNIQUE NOT NULL,
				token varchar(255) NOT NULL,
				PRIMARY KEY (id)
			);
			
        ");
    }

    public function register()
    {
        $this->connection->exec("INSERT INTO Migrations (name, applied) VALUES ('m_0002', now())");
    }
}