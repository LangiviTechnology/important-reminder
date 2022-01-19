<?php

use Langivi\ImportantReminder\Loader;
use Langivi\ImportantReminder\Services\DBService;

require './src/Migrations/migration.php';
require 'vendor/autoload.php';
require './src/Services/DBService.php';


$loader = Loader::boot();
$DB = $loader->getContainer("serviceDB");
$dbser = new DBService();
$dbser::setContainer($DB);
$dbconn =  DBService::$dbconn;
var_dump($dbconn);
$migration = new Migration($dbconn);
$sd = $migration->connectedDB();
$exmg = $migration->excudeMigration();

