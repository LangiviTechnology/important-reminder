<?php
require_once 'pdoconfig.php';
 
try {
    $dbconn = pg_connect("host=postgres dbname=reminderdb user=reminderuser password=4reminder321c");
    echo "Connected to $dbname at $host successfully.";
} catch (PDOException $pe) {
    die("Could not connect to the database $dbname :" . $pe->getMessage());
}