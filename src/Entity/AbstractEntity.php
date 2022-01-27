<?php
namespace  Langivi\ImportantReminder\Entity;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AbstractEntity
{
    public static  ContainerBuilder $containerBuilder;

    public  static function setContainer(ContainerBuilder $container)
    {
        self::$containerBuilder = $container;
    }
}