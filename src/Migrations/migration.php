<?php

use Langivi\ImportantReminder\Services\DBService;

// require_once('../Services/DBService.php');


class   Migration {
    public $dbconn;
    public $fileList;
    public function __construct($dbconn)
    {
        $this->dbconn = $dbconn;
        $this->fileList = $this->getMigrationFile();
    }
    public function connectedDB(){
        $dbconn = $this->dbconn;
        var_dump($dbconn);
        // $dbconn=pg_connect("host=postgres dbname=reminderdb user=reminderuser password=4reminder321c ");
        if (!$dbconn)
            throw new Exception('Could not connect to the database');
        else {
            $query = pg_query($dbconn,"SELECT 1");
        if (!$query)
            throw new Exception('Could not connect to the database');
        else
            return $this->dbconn = $dbconn;
        }
    }
    public function getMigrationFile(){
        $pgFolder = str_replace('\\', '/', realpath(dirname(__FILE__)) . '/pgSQL/');
        $allFiles = glob($pgFolder . '*.psql');
        var_dump($allFiles);
        $lastFileNumber = trim(preg_replace("/[^0-9]/",' ',end($allFiles)));
        
        $lastmigrationPath = dirname(__FILE__).'/lastmigration.txt';
        $lastmig  = explode(',',file_get_contents($lastmigrationPath));
        $lastmigNum = end($lastmig);
       
        if($lastFileNumber > $lastmigNum){
            $fileArr=array();
            foreach($allFiles as $file ){
               
                if(trim(preg_replace("/[^0-9]/",' ',$file))>$lastmigNum ){
                    array_push($fileArr,$file);
                }
            }
            return $fileArr;
        }else{
            return null;
        }
    
    }
    public function migrate($file){
        $migration = file_get_contents($file);
        pg_query($this->dbconn,$migration);
        $numbMigration = trim(preg_replace("/[^0-9]/",' ',$file));
        $filePath = dirname(__FILE__).'/lastmigration.txt';
        $current = file_get_contents($filePath);
        if(empty($current)){
            $current .= $numbMigration;
            file_put_contents($filePath, $current);
        }else{
        $current .= ",".$numbMigration;
        file_put_contents($filePath, $current);
        }
    }
    public function excudeMigration (){
        if($this->fileList){
            foreach($this->fileList as $file){
                $this->migrate($file);
            }
        }else {var_dump("Міграцій немає ");}
    }
}

// $mg= new Migration();
// var_dump($mg);
// $sd = $mg->connectedDB();
// $exmg = $mg->excudeMigration();