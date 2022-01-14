<?php 
use  Langivi\ImportantReminder\Services\DBService;

function connectDB() {
    $errorMessage = 'Невозможно подключиться к серверу базы данных';
    $dbconn=pg_pconnect("host=postgres dbname=reminderdb user=reminderuser password=4reminder321c");
    if (!$dbconn)
        throw new Exception($errorMessage);
    else {
        $query = pg_query($dbconn,"SELECT 1");
        if (!$query)
            throw new Exception($errorMessage);
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
    var_dump("lastmigration number",$lastmig);
    $lastmigNum = end($lastmig);
   

    if($lastFileNumber > $lastmigNum){
        var_dump("Выбираем те коротые не были выполнены ");
        $fileArr=array();
        
        foreach($allFiles as $file ){
           
            if(trim(preg_replace("/[^0-9]/",' ',$file))>$lastmigNum ){
                array_push($fileArr,$file);
            }
        }
        var_dump($fileArr);
        return $fileArr;
    }else{
        var_dump("Не делаем миграцию");
        return null;
    }
}

function migrate($dbconn,$file){
    var_dump('file in migration',$file);
    $migr = file_get_contents($file);
    var_dump($migr);
    pg_query($dbconn,$migr);
    // $command = sprintf( $file);    
    // // Выполняем shell-скрипт
    // shell_exec($command);
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
