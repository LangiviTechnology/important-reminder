<?php

namespace Langivi\ImportantReminder\Migrations;

use Langivi\ImportantReminder\Services\DbService;

class Migration
{
    public ?array $fileList;
    public $migrationList;

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
        if(!file_exists($lastmigrationPath)){
            file_put_contents($lastmigrationPath,"");
        }
        $fileArr = file($lastmigrationPath);
        if (!empty($fileArr)) {
            $lastmig = explode(',', $fileArr[0]);
            $this->migrationList = $lastmig;
        } else {
            $this->migrationList = [];
            $lastmig = [];
        }
        $fileArr = array();
        
        if($lastmig !== null){
            foreach ($allFiles as $file) {
                // $fileNumber = trim(preg_replace("/[^0-9]/", ' ', $file));
                preg_match_all("/\d+/",$file,$fileNumber);
                if ( !in_array($fileNumber[0][0],$lastmig)) {
                    array_push($fileArr, $file);
                    }
                }
                // var_dump("FILEARR IN SOTING", $fileArr);
            return $fileArr;
        } else {
            // var_dump("FILEARR IN SOTING ALL ", $allFiles);
            return $allFiles;
        }
    }

    public function recursionMigration(int $index=0){
        if ($this->fileList) {
            if (array_key_exists($index, $this->fileList)){
                $file = $this->fileList[$index];
                file_get_contents_async($file, function ($migration) use ($file, $index) {
                    $this->dbService->query(trim($migration))->then(function () use ($file, $index) {
                        $numbMigration = trim(preg_replace("/[^0-9]/", ' ', $file));
                        array_push($this->migrationList, $numbMigration);
                        $this->recursionMigration($index+1);
                    });
                });
            }else {
                // var_dump("in file wriyind EXIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIID");
                $filePath = dirname(__FILE__) . '/lastmigration.txt';
                file_put_contents_async($filePath, implode(',', $this->migrationList),fn() => var_dump("File written"));
                print("Міграцій більше не має\n");
            }
            
        } else {
            print("Міграцій немає \n");
        }
    }
}
