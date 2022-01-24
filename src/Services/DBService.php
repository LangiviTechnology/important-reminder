<?php
namespace Langivi\ImportantReminder\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;

    
class DBService
 {	
	private static ContainerBuilder $container;
	public $connecterDB;
	public function __construct() {
		$this->connecterDB =self::$container->get('dbconnecter');
	}
	public function querry($q){
		$connectDB = $this->connecterDB->getConnection();
		$quer = pg_send_query($connectDB,$q);
		return new \Promise(fn($res, $rej) => pg_wait($connectDB  , fn($arg) => $res(pg_fetch_all($arg))));
	}
	public function execute($par){
		$connectDB = $this->connecterDB->getConnection();
		$result = pg_send_execute($connectDB, "my_query", array($par));
		return new \Promise(fn($res, $rej) => pg_wait($connectDB, fn($arg) => $res(pg_fetch_all($arg))));
	}
	static function setContainer(ContainerBuilder $container): void
    {
		self::$container = $container;
    }
	
}