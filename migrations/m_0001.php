<?php
// Generated on 2023-04-03 19:51:59

declare(strict_types = 1);

namespace migrations;

use src\models\database\PhpMyAdmin;


class m_0001 extends PhpMyAdmin
{
    public function __construct()
    {
        parent::__construct();
    }

    public function operations()
    {
        $this->connection->exec(
            "CREATE TABLE IF NOT EXISTS User (
				id int NOT NULL AUTO_INCREMENT,
				uuid varchar(32) NOT NULL,
				first_mame varchar(32),
				last_name varchar(32),
				email varchar(32) NOT NULL,
				email_update timestamp NOT NULL,
				pass varchar(255) NOT NULL,
				pass_update timestamp NOT NULL,
				PRIMARY KEY (id)
			);
			
        ");
    }

    public function register()
    {
        $this->connection->exec("INSERT INTO Migrations (name, applied) VALUES ('m_0001', now())");
    }
}