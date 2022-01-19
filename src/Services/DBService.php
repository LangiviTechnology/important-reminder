<?php
namespace Langivi\ImportantReminder\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;

    
class DBService
 {	
	public static ContainerBuilder $container;
	 public static $dbconn;
	public function __construct() {
		$contBuild = $this::$container;
			$DB_HOST = $contBuild->getParameter('DB_HOST');
			$DB_NAME = $contBuild->getParameter('DB_NAME');
			$DB_USER = $contBuild->getParameter('DB_USER');
			$DB_PASSWORD = $contBuild->getParameter('DB_PASSWORD');
		self::$dbconn = pg_connect(" host=$DB_HOST dbname=$DB_NAME user=$DB_USER password=$DB_PASSWORD ");
		
	}
	public function connectDB(){
		try {
			if (!$this->dbconn){
			 var_dump("Could not connect to the database");
			} else {
				$query = pg_query($this->dbconn,"SELECT 1");
			 	if (!$query){
				 	var_dump("Could not connect to the database");
				} else return true;
				
			}

    	} catch (e) {
        	die("Could not connect to the database");
    	}
    }
	public function querry($q){
		pg_send_query($this->dbconn,$q);
		return new Promise(fn($res, $rej) => pg_wait($this->dbconn, fn($arg) => $res(pg_fetch_all($arg))));
	}
	public function execute($par){
		$result = pg_send_prepare($this->dbconn, "my_query", 'SELECT * FROM shops WHERE name = $par');
		$result = pg_send_execute($this->dbconn, "my_query", array($par));
		return new Promise(fn($res, $rej) => pg_wait($this->dbconn, fn($arg) => $res(pg_fetch_all($arg))));
	}
	static function setContainer(ContainerBuilder $container): void
    {
		self::$container = $container;
    }
	
}