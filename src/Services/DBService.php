<?php
namespace Langivi\ImportantReminder\Services;

class DBService
{
    public function connectDB(){
    	try {
        	$dbconn = pg_pconnect("host=postgres dbname=reminderdb user=reminderuser password=4reminder321c");
        	echo "Connected to postgres at postgres successfully.";
			$result = pg_query($dbconn, "SELECT *");
			echo $result;
    	} catch (e) {
        	die("Could not connect to the database");
    	}
    }
}