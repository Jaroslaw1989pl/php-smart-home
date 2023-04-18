<?php

// TODO: Transactions https://www.youtube.com/watch?v=e6yLUvpcOZo&list=PLr3d3QYzkw2xabQRUpcZ_IBk9W50M9pe-&index=65

declare(strict_types = 1);

namespace src\models\database;


class PostgreSQL
{
    private string $host = POSTGRESQL_CONFIG['host'];
    private string $port = POSTGRESQL_CONFIG['port'];
    private string $base = POSTGRESQL_CONFIG['base'];
    private string $user = POSTGRESQL_CONFIG['user'];
    private string $pass = POSTGRESQL_CONFIG['pass'];

    protected $connection = null;

    public function __construct()
    {
        try
        {
            $this->connection = new \PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->base;user=$this->user;password=$this->pass");
            // set the PDO error mode to exception
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        catch (\PDOException $error)
        {
            echo "Connection failed: " . $error->getMessage();
        }
    }

    public function __destruct()
    {
        $this->connection = null;
    }

    protected function insert(string $query, array $values): void
    {
        $statement = $this->connection->prepare($query);

        $statement->execute($values);
    }

    protected function update(string $query, array $values): int
    {
        $statement = $this->connection->prepare($query);

        $statement->execute($values);

        return $statement->rowCount();
    }

    protected function select(string $query, array $values): array
    {
        $statement = $this->connection->prepare($query);

        $statement->execute($values);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function delete(string $query, array $values): void
    {
        $statement = $this->connection->prepare($query);

        $statement->execute($values);
    }
}