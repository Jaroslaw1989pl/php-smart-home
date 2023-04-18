<?php

declare(strict_types = 1);

namespace app\tools;

use src\models\database\Model;

date_default_timezone_set("Europe/Warsaw");


class Migrations
{
    private string $db;

    public function __construct()
    {
        $this->db = PHPMYADMIN_CONFIG['base'];
        
        // checking if migrations directory and initial file exists
        if (file_exists(\ROOT_DIR."/migrations") === false)
            mkdir(\ROOT_DIR."/migrations");
        // creating initial file
        if (file_exists(\ROOT_DIR."/migrations/_initial.php") === false)
        {
            $initialFile = fopen(\ROOT_DIR."/migrations/_initial.php", "w");
            fwrite($initialFile, sprintf(INITIAL_TEMPLATE, date("Y-m-d G:i:s")));
            fclose($initialFile);
        }
        // creating Migrations table in database
        if (file_exists(\ROOT_DIR."/migrations/_initial.php"))
        {
            include ROOT_DIR."/migrations/_initial.php";
            $migrationClass = new \migrations\MigrationSchema();
            $migrationClass->initialize();
        }
    }

    function __toString()
    {
        $string = <<<FRAGMENT
        Migrations commands:
        > migrations:
        FRAGMENT.PHP_EOL;

        foreach (get_class_methods(__CLASS__) as $method)
            if (!str_starts_with($method, "_"))
                $string .= "\t$method".PHP_EOL;

        return $string;
    }

    function __call($name, $arguments)
    {
        throw new \Exception("Mmethod \"$name\" does not exists in class Migrations.", 4);
    }

    public function migrate()
    {
        // checking last applied migration
        $migrationClass = new \migrations\MigrationSchema();
        $lastMigration  = $migrationClass->getLastMigration();
        
        $migrationFiles = scandir(\ROOT_DIR."/migrations");
        $migrationFiles = array_filter($migrationFiles, fn ($file) => str_starts_with($file, "m_"));
        $migrationFiles = array_map(fn ($file) => rtrim($file, ".php"), $migrationFiles);

        if (count($migrationFiles) && (end($migrationFiles) !== end($lastMigration)['name'] || empty($lastMigration)))
        {
            foreach ($migrationFiles as $file)
            {
                $migrationNamespace = "\migrations\\$file";
                $migrationClass     = new $migrationNamespace();

                $migrationClass->operations();
                $migrationClass->register();
            }
        }
        else echo "Migrations are up to date.".PHP_EOL;
    }

    public function make()
    {
        $snapshot     = [];
        $operations   = "";
        $snapshotPath = \ROOT_DIR."/app/tools/migration_snapshot.json";
        $snapshotSize = filesize($snapshotPath);
        $snapshotFile = fopen($snapshotPath, "r");

        $snapshot     = json_decode(fread($snapshotFile, $snapshotSize), true);
        fclose($snapshotFile);

        $allFiles     = scandir(\ROOT_DIR."/src/models/schemas");
        $schemaFiles  = array_filter($allFiles, fn ($file) => !str_starts_with($file, "."));
        $schemaFiles  = array_map(fn ($file) => rtrim($file, ".php"), $schemaFiles);
        
        $snapshotSchemas = array_keys($snapshot['database'][$this->db]['tables']);

        $schemas = array_merge($schemaFiles, $snapshotSchemas);
        $schemas = array_unique($schemas);
        
        foreach ($schemas as $schema)
        {
            if (in_array($schema, $snapshotSchemas) && !in_array($schema, $schemaFiles))
            {
                // DROP TABLE
                unset($snapshot['database'][$this->db]['tables'][$schema]);
                $operations .= Model::dropTable($schema);
            }
            else if (!in_array($schema, $snapshotSchemas) && in_array($schema, $schemaFiles))
            {
                // CREATE TABLE
                $jsonFields     = [];
                $databaseFields = [];
                $schemaClass    = "\src\models\schemas\\$schema";
                $schemaObject   = new $schemaClass();

                foreach (get_object_vars($schemaObject) as $attribute => $value)
                {    
                    $jsonFields[$attribute] = $value['array'];
                    $databaseFields[$attribute] = $value['string'];
                }

                $snapshot['database'][$this->db]['tables'][$schema] = $jsonFields;
                $operations .= Model::createTable($schema, $databaseFields);
            }
            else if (in_array($schema, $snapshotSchemas) && in_array($schema, $schemaFiles))
            {
                // ALTER TABLE
                $schemaClass        = "\src\models\schemas\\$schema";
                $schemaAttributes   = get_object_vars(new $schemaClass());
                $schemaKeys         = array_keys($schemaAttributes);
                $snapshotAttributes = $snapshot['database'][$this->db]['tables'][$schema];
                $snapshotKeys       = array_keys($snapshotAttributes);
                $attributes         = array_unique(array_merge($schemaKeys, $snapshotKeys));

                foreach ($attributes as $attribute)
                {
                    if (in_array($attribute, $snapshotKeys) && !in_array($attribute, $schemaKeys))
                    {
                        // DROP COLUMN
                        unset($snapshot['database'][$this->db]['tables'][$schema][$attribute]);
                        $operations .= Model::alterTableDropColumn($schema, $attribute);
                    }
                    else if (!in_array($attribute, $snapshotKeys) && in_array($attribute, $schemaKeys))
                    {
                        // ADD COLUMN
                        $snapshot['database'][$this->db]['tables'][$schema][$attribute] = $schemaAttributes[$attribute]['array'];
                        $operations .= Model::alterTableAddColumn($schema, $attribute, $schemaAttributes[$attribute]['string']);
                    }
                }
            }
        }

        if ($operations) $this->_save($snapshot, $operations);
        else echo "No operations was applied.".PHP_EOL;
    }

    private function _save($snapshot, $operations)
    {
        $snapshot['migrationsCount']++;

        $snapshotPath = \ROOT_DIR."/app/tools/migration_snapshot.json";
        $snapshotFile = fopen($snapshotPath, "w");

        fwrite($snapshotFile, json_encode($snapshot));
        fclose($snapshotFile);

        $migrationPath = \ROOT_DIR."/migrations/m_".sprintf("%04d", $snapshot['migrationsCount']).".php";
        $migrationFile = fopen($migrationPath, "w");

        fwrite($migrationFile, sprintf(MIGRATION_TEMPLATE,
            date("Y-m-d G:i:s"),
            "m_".sprintf("%04d", $snapshot['migrationsCount']),
            $operations,
            "m_".sprintf("%04d", $snapshot['migrationsCount'])
        ));
        fclose($migrationFile);
    }
}


const INITIAL_TEMPLATE = <<<TEMPLATE
<?php
// Generated on %s

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
        \$this->connection->exec(
            "CREATE TABLE if not exists Migrations (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(30) NOT NULL,
                applied TIMESTAMP NOT NULL
            )"
        );
    }

    public function getLastMigration()
    {
        \$statement = \$this->connection->prepare("SELECT * FROM Migrations");

        \$statement->execute();

        return \$statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}
TEMPLATE;

const MIGRATION_TEMPLATE = <<<TEMPLATE
<?php
// Generated on %s

declare(strict_types = 1);

namespace migrations;

use src\models\database\PhpMyAdmin;


class %s extends PhpMyAdmin
{
    public function __construct()
    {
        parent::__construct();
    }

    public function operations()
    {
        \$this->connection->exec(
            "%s
        ");
    }

    public function register()
    {
        \$this->connection->exec("INSERT INTO Migrations (name, applied) VALUES ('%s', now())");
    }
}
TEMPLATE;