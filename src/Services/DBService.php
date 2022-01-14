<?php
namespace Langivi\ImportantReminder\Services;

class DBService
 {	
	 public $dbconn;
	public function __construct() {
		$this->dbconn = pg_pconnect("host=postgres dbname=reminderdb user=reminderuser password=4reminder321c");
	}
	public function connectDB(){
		try {
			echo "Connected to postgres at postgres successfully.";
		
			pg_send_query($this->dbconn, "CREATE DATABASE reminderDB");
			pg_wait($this->dbconn,function ($arg){
				var_dump (pg_fetch_all($arg));
			});

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
}