<?php

class migration_003_table_pwd_reset
{
    public function __construct(private \PDO $pdo) { }

    public function up()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS pwd_reset (
            id INT PRIMARY KEY AUTO_INCREMENT,
            email varchar(255),
            token VARCHAR(255),
            expire INT
        )");
    }

    public function down()
    {
        $this->pdo->exec("DROP TABLE pwd_reset");
    }
}