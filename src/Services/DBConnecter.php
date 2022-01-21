<?php
namespace Langivi\ImportantReminder\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;

    
class DBConnecter
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
	static function setContainer(ContainerBuilder $container): void
    {
		self::$container = $container;
    }
	
}