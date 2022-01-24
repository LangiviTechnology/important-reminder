<?php

use Langivi\ImportantReminder\Loader;
use Langivi\ImportantReminder\Migrations\Migration;

require 'vendor/autoload.php';
require './src/Migrations/migration.php';



$loader = Loader::bootCli();
$container = $loader->getContainer();
/**
 * @var $migration Migration
 */
$migration = $container->get(Migration::class);
//$migration->excludeMigration();

