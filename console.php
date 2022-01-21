<?php

use Langivi\ImportantReminder\Loader;

require 'vendor/autoload.php';
require './src/Migrations/migration.php';



$loader = Loader::bootCli();
$DB = $loader->getContainer();
$dbconn = $DB->get('dbconnecter')::$dbconn;
var_dump($dbconn);
$migration = new Migration($dbconn);
$exmg = $migration->excludeMigration();

