<?php

class migration_002_table_profiles
{
    public function __construct(private \PDO $pdo) { }

    public function up()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS profiles (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id VARCHAR(64),
            first_name VARCHAR(64),
            last_name VARCHAR(64),
            phone VARCHAR(64),
            location VARCHAR(64),
            avatar VARCHAR(64)
        )");
    }

    public function down()
    {
        $this->pdo->exec("DROP TABLE profiles");
    }
}