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
        pg_send_query($connectDB, $query);
        var_dump($query);
        return new \Promise(fn($res, $rej) => $res(pg_get_result($connectDB)));
        // return new \Promise(fn($res, $rej) =>  pg_wait($connectDB, function ($arg) use($res) {
        //     var_dump($arg,"ARGUMENT IN PG_WAIT");
        //     $res($arg);
        // }
        // ));
    }

    public function prepare(string $statementName, string $statement): \Promise
    {
        $connectDB = $this->connecterDB->getConnection();
        return new \Promise(fn($res, $rej) => pg_send_prepare($connectDB, $statementName, $statement) & pg_wait($connectDB, fn($arg) => $res($arg)));
    }

    public function execute(string $statement, array $params = [])//: \Promise
    {
        $connectDB = $this->connecterDB->getConnection();
        var_dump($connectDB);
        pg_send_execute($connectDB, $statement, $params);
//        var_dump(pg_get_result());
    //    return new \Promise(fn($res, $rej) => pg_wait($connectDB, fn($arg) => $res($arg)));
    }

    public function delay($cb): \Promise
    {
        static $timer = 1000;
        $timer+=1000;
        return new \Promise(fn($res, $rej) => set_timeout(fn() => $res($cb()), $timer));
    }

    static function setContainer(ContainerBuilder $container): void
    {
        self::$container = $container;
    }

}