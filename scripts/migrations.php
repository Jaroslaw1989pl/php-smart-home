<?php

require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../app/config/config.php";


$host = DB_CONFIG['host'];
$port = DB_CONFIG['port'];
$base = DB_CONFIG['base'];
$user = DB_CONFIG['user'];
$pass = DB_CONFIG['pass'];

$connection = null;

try
{
    $connection = new \PDO("pgsql:host=$host;port=$port;dbname=$base;user=$user;password=$pass");
    // set the PDO error mode to exception
    $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
}
catch (\PDOException $error)
{
    echo "Connection failed: " . $error->getMessage();
}

$migrationFiles = scandir(ROOT_DIR.'/migrations');

foreach($migrationFiles as $file)
{
    if (str_starts_with($file, 'migration_'))
    {
        require_once ROOT_DIR.'/migrations/'.$file;

        $class = pathinfo($file, PATHINFO_FILENAME);
        $object = new $class($connection);

        $object->up();
    }
}


$dbConnection = null;

