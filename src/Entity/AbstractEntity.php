<?php
namespace  Langivi\ImportantReminder\Entity;
use Langivi\ImportantReminder\Services\DbService;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AbstractEntity
{
    public static  ContainerBuilder $containerBuilder;
    protected DbService | null $dbService;

    public  static function setContainer(ContainerBuilder $container)
    {
        self::$containerBuilder = $container;
    }
}