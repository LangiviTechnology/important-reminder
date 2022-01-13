<?php
namespace Langivi\ImportantReminder\Services;

class DBService
{
    public function connectDB(){
    	try {
        	$dbconn = pg_pconnect("host=postgres dbname=reminderdb user=reminderuser password=4reminder321c");
        	echo "Connected to postgres at postgres successfully.";

			pg_send_query($dbconn, "SELECT 1");
			pg_wait($dbconn,function ($arg){
				var_dump (pg_fetch_all($arg));
			});

    	} catch (e) {
        	die("Could not connect to the database");
    	}
    }
	public function querry($q){
		$dbconn = pg_pconnect("host=postgres dbname=reminderdb user=reminderuser password=4reminder321c");
		pg_send_query($dbconn,$q);
		return new Promise(fn($res, $rej) => pg_wait($dbconn, fn($arg) => $res(pg_fetch_all($arg))));
	}
	public function execute($par){
		$dbconn = pg_pconnect("host=postgres dbname=reminderdb user=reminderuser password=4reminder321c");
		$result = pg_send_prepare($dbconn, "my_query", 'SELECT * FROM shops WHERE name = $1');
		$result = pg_send_execute($dbconn, "my_query", array($par));
		return new Promise(fn($res, $rej) => pg_wait($dbconn, fn($arg) => $res(pg_fetch_all($arg))));
	}
	

}