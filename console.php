<?php

use Langivi\ImportantReminder\Loader;

require 'vendor/autoload.php';
require './src/Migrations/migration.php';



$loader = Loader::bootCli();
$container = $loader->getContainer();
$dbconn = $container->get('dbconnecter')->getConnection() ;
var_dump($dbconn);
$migration = new Migration($dbconn);
$exmg = $migration->excludeMigration();

