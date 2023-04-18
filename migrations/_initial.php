<?php
// Generated on 2023-04-03 19:29:26

declare(strict_types = 1);

namespace migrations;

use src\models\database\PhpMyAdmin;


class MigrationSchema extends PhpMyAdmin
{
    public function __construct()
    {
        parent::__construct();
    }

    public function initialize()
    {
        $this->connection->exec(
            "CREATE TABLE if not exists Migrations (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(30) NOT NULL,
                applied TIMESTAMP NOT NULL
            )"
        );
    }

    public function getLastMigration()
    {
        $statement = $this->connection->prepare("SELECT * FROM Migrations");

        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}