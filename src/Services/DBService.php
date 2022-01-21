<?php
namespace Langivi\ImportantReminder\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;

    
class DBService
 {	
	public static ContainerBuilder $container;
	 public static $dbconn;
	public function __construct() {
		self::$dbconn = $this::$container->get('dbconnecter')::$dbconn;
	}
	public static function querry($q){
		
		$quer = pg_send_query(self::$dbconn,$q);
		return new \Promise(fn($res, $rej) => pg_wait(self::$dbconn, fn($arg) => $res(pg_fetch_all($arg))));
	}
	public static function execute($par){
		$result = pg_send_prepare(self::$dbconn, "my_query", 'SELECT * FROM shops WHERE name = $par');
		$result = pg_send_execute(self::$dbconn, "my_query", array($par));
		return new \Promise(fn($res, $rej) => pg_wait(self::$dbconn, fn($arg) => $res(pg_fetch_all($arg))));
	}
	static function setContainer(ContainerBuilder $container): void
    {
		self::$container = $container;
    }
	
}