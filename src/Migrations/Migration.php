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
        $lastFileNumber = trim(preg_replace("/[^0-9]/", ' ', end($allFiles)));

        $lastmigrationPath = dirname(__FILE__) . '/lastmigration.txt';
        $fileArr = file($lastmigrationPath);
        if (!empty($fileArr)) {
            $lastmig = explode(',', $fileArr[0]);
            $lastmigNum = end($lastmig);
        } else {
            $lastmigNum = 0;
        }

        if ($lastFileNumber > $lastmigNum) {
            $fileArr = array();
            foreach ($allFiles as $file) {

                if (trim(preg_replace("/[^0-9]/", ' ', $file)) > $lastmigNum) {
                    array_push($fileArr, $file);
                }
            }
            return $fileArr;
        } else {
            return null;
        }

    }

    public function migrate($file)
    {
        file_get_contents_async($file, function ($migration) use ($file) {
            $this->dbService->query(trim($migration))->then(function () use ($file) {
                $numbMigration = trim(preg_replace("/[^0-9]/", ' ', $file));
                $filePath = dirname(__FILE__) . '/lastmigration.txt';
                var_dump($file, $filePath);
                if (file_exists($filePath)) {
                    file_get_contents_async($filePath, function ($current) use ($numbMigration, $filePath) {
                        var_dump($current);
                        $migrations = explode(',', $current);
                        $migrations[] = $numbMigration;
                        var_dump($migrations);
                        file_put_contents_async($filePath, implode(',', $migrations), fn() => var_dump("File written"));
                    });
                } else {
                    file_put_contents_async($filePath, implode(',', [$numbMigration]), fn() => var_dump("File written"));
                }
            });
        });
    }

    public function excludeMigration()
    {
        if ($this->fileList) {
            foreach ($this->fileList as $file) {
                $this->migrate($file);
            }
        } else {
            var_dump("Міграцій немає ");
        }
    }
}
