<?php

namespace Langivi\ImportantReminder\Migrations;

use Langivi\ImportantReminder\Services\DbService;

class Migration
{
    public ?array $fileList;

    public function __construct(public readonly DbService $dbService,
    )
    {
        $this->fileList = $this->getMigrationFile();
    }

    public function getMigrationFile()
    {
        $pgFolder = str_replace('\\', '/', realpath(dirname(__FILE__)) . '/pgSQL/');
        $allFiles = glob($pgFolder . '*.psql');
        // $lastFileNumber = trim(preg_replace("/[^0-9]/", ' ', end($allFiles)));
        sort($allFiles, SORT_NATURAL);

        $lastmigrationPath = dirname(__FILE__) . '/lastmigration.txt';
        $fileArr = file($lastmigrationPath);
        if (!empty($fileArr)) {
            $lastmig = explode(',', $fileArr[0]);
        } else {
            $lastmigNum = 0;
        }
        $fileArr = array();
        
        if($lastmig !== null){
            foreach ($allFiles as $file) {
                $fileNumber = trim(preg_replace("/[^0-9]/", ' ', $file));
                if ( !in_array($fileNumber,$lastmig)) {
                    array_push($fileArr, $file);
                    }
                }
                var_dump("FILEARR IN SOTING", $fileArr);
            return $fileArr;
        } else {
            var_dump("FILEARR IN SOTING ALL ", $allFiles);
            return $allFiles;
        }
    }

    public function migrate($file)
    {
        file_get_contents_async($file, function ($migration) use ($file) {
            var_dump("file in migration",$file);
            // $this->dbService->query(trim($migration))->then(function () use ($file) {
            //     $numbMigration = trim(preg_replace("/[^0-9]/", ' ', $file));
            //     $filePath = dirname(__FILE__) . '/lastmigration.txt';
            //     var_dump("file in MIGRATION",$file);
            //     if (file_exists($filePath)) {
            //         file_get_contents_async($filePath, function ($current) use ($numbMigration, $filePath) {
            //             // var_dump($current);
            //             $migrations = explode(',', $current);
            //             $migrations[] = $numbMigration;
            //             // var_dump($migrations);
            //             file_put_contents_async($filePath, implode(',', $migrations), fn() => var_dump("File written"));
            //         });
            //     } else {
            //         file_put_contents_async($filePath, implode(',', [$numbMigration]), fn() => var_dump("File written"));
            //     }
            // });
        });
    }

    public function excludeMigration()
    {  
        if ($this->fileList) {
            foreach ($this->fileList as $file) {
                var_dump("file for MIGRATION", $file);
                $this->migrate($file);
            }
        } else {
            var_dump("Міграцій немає ");
        }
    }
    public function recursionMigration($index=0){
        if ($this->fileList) {
            if (array_key_exists($index,$this->fileList)){
                $file = $this->fileList[$index];
                var_dump("FILE IN RECURSEMIGRATION",$file);
                file_get_contents_async($file, function ($migration) use ($file,$index) {
                    $this->dbService->query(trim($migration))->then(function () use ($file,$index) {
                        $numbMigration = trim(preg_replace("/[^0-9]/", ' ', $file));
                        $filePath = dirname(__FILE__) . '/lastmigration.txt';
                        var_dump("FILE IN QUERRY THEN ", $file);
                       
                        if (file_exists($filePath)) {
                            file_get_contents_async($filePath, function ($current) use ($numbMigration, $filePath, $index) {
                                $migrations = explode(',', $current);
                                $migrations[] = $numbMigration;
                                file_put_contents_async($filePath, implode(',', $migrations),fn() => var_dump("File written"));
                                var_dump("INDEX IN AFTER PUT CONTENT",$index);
                                $this->recursionMigration($index+1);
                            });
                        } else {
                            file_put_contents_async($filePath, implode(',', [$numbMigration]), fn() => var_dump("File written"));
                            var_dump("INDEX IN AFTER PUT CONTENT",$index);
                            $this->recursionMigration($index+1);
                        }
                        
                    });
                });
            }else {
                var_dump("Міграцій більше не має");
            }
            
        } else {
            var_dump("Міграцій немає ");
        }
    }
}
