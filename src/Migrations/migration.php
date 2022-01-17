<?php 

function connectDB() {
    
    $dbconn=pg_connect("host=postgres dbname=reminderdb user=reminderuser password=4reminder321c ");
    
    if (!$dbconn)
        throw new Exception('Could not connect to the database');
    else {
        $query = pg_query($dbconn,"SELECT 1");
        if (!$query)
            throw new Exception('Could not connect to the database');
        else
            return $dbconn;
    }
}

function getMigrationFile(){
    $pgFolder = str_replace('\\', '/', realpath(dirname(__FILE__)) . '/pgSQL/');
    $allFiles = glob($pgFolder . '*.psql');
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

function migrate($dbconn,$file){
    $migration = file_get_contents($file);
    pg_query($dbconn,$migration);
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
$dbconn = connectDB();
$fileList = getMigrationFile();
if($fileList){
    foreach($fileList as $file){
        migrate($dbconn,$file);
    }
}
