<?php
namespace Langivi\ImportantReminder\Services;

use Symfony\Component\DependencyInjection\ContainerBuilder;

    
class DBConnecter
{	
	private static ContainerBuilder $container;
	private $connectDB;
	public function __construct() {
		$container = $this::$container;
			$DB_HOST = $container->getParameter('DB_HOST');
			$DB_NAME = $container->getParameter('DB_NAME');
			$DB_USER = $container->getParameter('DB_USER');
			$DB_PASSWORD = $container->getParameter('DB_PASSWORD');
		$this->connectDB = pg_connect(" host=$DB_HOST dbname=$DB_NAME user=$DB_USER password=$DB_PASSWORD ");
		
	}
	static function setContainer(ContainerBuilder $container): void
	{
		self::$container = $container;
	}
	function getConnection (){
		return $this->connectDB;
	}
}