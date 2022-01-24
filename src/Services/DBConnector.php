<?php

namespace Langivi\ImportantReminder\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;


class DBConnector
{
    private readonly  \PgSql\Connection|false $connectDB;

    public function __construct($dbHost = '0.0.0.0', $dbName = '', $dbUser = 'root', $dbPassword = null)
    {
        $this->connectDB = pg_connect("host=$dbHost dbname=$dbName user=$dbUser password=$dbPassword");
    }

    function getConnection()
    {
        return $this->connectDB;
    }
}