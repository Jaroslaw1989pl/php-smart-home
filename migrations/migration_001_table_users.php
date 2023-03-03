<?php

class migration_001_table_users
{
    public function __construct(private \PDO $pdo) { }

    public function up()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS public.php_users
        (
            id integer NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 ),
            uuid text NOT NULL,
            email text NOT NULL,
            email_update timestamp without time zone NOT NULL,
            pass text NOT NULL,
            pass_update timestamp without time zone NOT NULL,
            PRIMARY KEY (id)
        );");
        
        $this->pdo->exec("ALTER TABLE IF EXISTS public.php_users OWNER to postgres;");
    }

    public function down()
    {
        $this->pdo->exec("DROP TABLE php_users");
    }
}