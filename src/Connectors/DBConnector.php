<?php

namespace Langivi\ImportantReminder\Connectors;

use Symfony\Component\DependencyInjection\ContainerBuilder;


class DBConnector
{
    private readonly  \PgSql\Connection $connectDB;

    public function __construct($dbHost = '0.0.0.0', $dbName = '', $dbUser = 'root', $dbPassword = null)
    {
        $connection = pg_connect("host=$dbHost dbname=$dbName user=$dbUser password=$dbPassword");
        if ($connection) {
            $this->connectDB = $connection;
        }
        if (!isset($this->connectDB)) {
            set_timeout(fn()=>($this->connectDB = pg_connect("host=$dbHost dbname=$dbName user=$dbUser password=$dbPassword")),5000);
        }
        // var_dump($connection);
    }

    function getConnection()
    {
        return $this->connectDB;
    }
}