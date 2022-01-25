<?php

namespace Langivi\ImportantReminder\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;


class DbService
{
    private static ContainerBuilder $container;
    public $connecterDB;

    public function __construct()
    {
        $this->connecterDB = self::$container->get('db_connecter');
    }

    public function query($query): \Promise
    {
        $connectDB = $this->connecterDB->getConnection();
        pg_send_query($connectDB, $query);
        return new \Promise(fn($res, $rej) => pg_wait($connectDB, fn($arg) => $res(pg_fetch_all($arg))));
    }

    public function execute(string $statement, array $params=[]): \Promise
    {   
        $connectDB = $this->connecterDB->getConnection();
        pg_send_execute($connectDB, $statement, $params);
        return new \Promise(fn($res, $rej) => pg_wait($connectDB, fn($arg) => $res(pg_fetch_all($arg))));
    }

    static function setContainer(ContainerBuilder $container): void
    {
        self::$container = $container;
    }

}