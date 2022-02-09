<?php

namespace Langivi\ImportantReminder\Services;

use Langivi\ImportantReminder\Connectors\DBConnector;
use Symfony\Component\DependencyInjection\ContainerBuilder;


class DbService
{
    private static ContainerBuilder $container;
    public DBConnector $connecterDB;

    public function __construct()
    {
        $this->connecterDB = self::$container->get('db_connecter');
    }

    public function query($query): \Promise
    {
        $connectDB = $this->connecterDB->getConnection();
        return new \Promise(fn($res, $rej) => pg_wait($connectDB,
            fn() => $query,
            fn(\PgSql\Result|false $result) => $result ? $res($result) : $rej($result)));
    }

    public function prepare(string $statementName, string $statement): \Promise
    {
        $connectDB = $this->connecterDB->getConnection();
        // var_dump("IN PREPARE",$statementName,$statement);
        pg_send_prepare($connectDB, $statementName, $statement);
        return new \Promise(fn($res, $rej) => pg_wait($connectDB, fn($arg) => $res($arg)));
    }

    public function execute(string $statement, array $params = [])//: \Promise
    {

        $connectDB = $this->connecterDB->getConnection();
        // var_dump("IN EXECUTE",$params,$statement);
        pg_send_execute($connectDB, $statement, $params);
        return new \Promise(fn($res, $rej) => pg_wait($connectDB, fn($arg) => $res($arg)));
    }

    public function delay($cb): \Promise
    {
        static $timer = 1000;
        $timer += 1000;
        return new \Promise(fn($res, $rej) => set_timeout(fn() => $res($cb()), $timer));
    }

    static function setContainer(ContainerBuilder $container): void
    {
        self::$container = $container;
    }

}