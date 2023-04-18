<?php

declare(strict_types = 1);

namespace src\models\database;


class Model extends PhpMyAdmin
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add(array $data): void
    {
        $table   = end(explode("\\", get_class($this)));
        $columns = array_keys($data);
        $query   = "INSERT INTO $table (%s) VALUES (%s)";
        $query   = sprintf($query, join(", ", $columns), join(", ", array_map(fn ($key) => ":".$key, $columns)));

        $this->insert($query, $data);
    }

    public function get(array $data): array
    {
        $table      = end(explode("\\", get_class($this)));
        $fields     = get_object_vars($this);
        $columns    = array_values(array_diff(array_keys($fields), ["connection"]));
        $conditions = "";
        
        if (!empty($data))
        {            
            $conditions = " WHERE";
            foreach ($data as $key => $value) $conditions .= " $key = :$key";
        }
        
        $query = "SELECT %s FROM $table$conditions";
        $query = sprintf($query, join(", ", $columns));

        return $this->select($query, $data)[0] ?? $this->select($query, $data);
    }

    // public function getOne(array $data): array
    // {
    //     return $this->get($data)[0];
    // }

    // public function getAll(array $data): array
    // {
    //     return $this->get($data);
    // }

    public function set(array $data, array $condition): void
    {
        $table      = end(explode("\\", get_class($this)));
        $columns    = [];
        $conditions = " WHERE";

        foreach ($data as $key => $value) array_push($columns, "$key = :$key");

        foreach ($condition as $key => $value) $conditions .= " $key = :$key";

        $query = "UPDATE $table SET ".join(", ", $columns).$conditions;

        $this->update($query, array_merge($data, $condition));
    }

    public function remove(array $data): void
    {
        $table = end(explode("\\", get_class($this)));
        
        if (!empty($data))
        {            
            $conditions = " WHERE";

            foreach ($data as $key => $value) $conditions .= " $key = :$key";

            $query = "DELETE FROM $table$conditions";
    
            $this->delete($query, $data);
        }        
    }

    protected static function varchar(int $length = 255, bool $unique = false, bool $nullable = true): array
    {
        return [
            "array" => ["type" => __FUNCTION__, "length" => $length, "nullable" => $nullable],
            "string" => sprintf(__FUNCTION__."(%s)%s%s", $length, $unique ? " UNIQUE" : "", $nullable ? "" : " NOT NULL")
        ];
    }

    protected static function int(bool $unique = false, bool $nullable = true): array
    {
        return [
            "array" => ["type" => __FUNCTION__, "nullable" => $nullable],
            "string" => sprintf(__FUNCTION__."%s%s", $unique ? " UNIQUE" : "", $nullable ? "" : " NOT NULL")
        ];
    }

    protected static function text(bool $unique = false, bool $nullable = true): array
    {
        return [
            "array" => ["type" => __FUNCTION__, "nullable" => $nullable],
            "string" => sprintf(__FUNCTION__."%s%s", $unique ? " UNIQUE" : "", $nullable ? "" : " NOT NULL")
        ];
    }

    protected static function timestamp(bool $unique = false, bool $nullable = true): array
    {
        return [
            "array" => ["type" => __FUNCTION__, "nullable" => $nullable],
            "string" => sprintf(__FUNCTION__."%s%s", $unique ? " UNIQUE" : "", $nullable ? "" : " NOT NULL")
        ];
    }

    public static function createTable(string $table, array $fields): string
    {
        $query  = "CREATE TABLE IF NOT EXISTS $table (\n";
        $query .= "\t\t\t\tid int NOT NULL AUTO_INCREMENT,\n";

        foreach ($fields as $key => $value) $query .= "\t\t\t\t$key $value,\n";

        $query .= "\t\t\t\tPRIMARY KEY (id)\n\t\t\t);\n\t\t\t";

        return $query;
    }

    public static function dropTable(string $table): string
    {
       return "DROP TABLE IF EXISTS $table;\n\t\t\t";
    }

    public static function alterTableAddColumn(string $table, string $field, string $type): string
    {
        return "ALTER TABLE $table ADD COLUMN $field $type;\n\t\t\t";
    }

    public static function alterTableDropColumn(string $table, string $field): string
    {
        return "ALTER TABLE $table DROP COLUMN IF EXISTS $field;\n\t\t\t";
    }
}