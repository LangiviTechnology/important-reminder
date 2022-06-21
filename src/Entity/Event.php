<?php

namespace Langivi\ImportantReminder\Entity;

use Langivi\ImportantReminder\utils\ExtendedPromise;
use DateTime;
use Langivi\ImportantReminder\Connectors\DBConnector;
use Langivi\ImportantReminder\Services\DbService;

class Event extends AbstractEntity
{
    private int $id;

    private string $title;

    private string $description;

    private string $type;

    private DateTime $date;

    private DateTime $date_remind;

    private DateTime $date_created;

    public function __construct()
    {


    }

    public static function create()
    {
        // $object = new self();
        // $db=$object->dbService = self::$containerBuilder->get(DbService::class);
        // return $db->prepare("addEvent", 'INSERT INTO event VALUES (DEFAULT,$1,$2,$3)')->then(function($data) use(&$object){
        //     return \Promise::resolve($object);
        // });
        //-----------------------------------------------------------------------------------
        // $object = new self();
        // $db=$object->dbService = self::$containerBuilder->get(DbService::class);
        // return ExtendedPromise::allRecurs([
        //     $object->dbService->prepare("addEvent", 'INSERT INTO event VALUES ($1,$2,$3,$4,$5) '),
        //    $object->dbService->prepare("removeEvent", 'DELETE FROM event WHERE id = $1'),
        // //   $object->dbService->prepare("updateEvent", 'UPDATE event SET title = $2 , description = $3 , type = $4 , date = $5, date_remind = $6 WHERE id = $1 '),
        // ])->then(function($data) use(&$object){
        //     var_dump("PROOOOOMISE ALLLLLL",$data);
        // });
        //-----------------------------------------------------------------------------------
        // $object->dbService = self::$containerBuilder->get(DbService::class);
        // $connection = $object->dbService->connecterDB->getConnection();
        // //temp solution!
        // pg_send_prepare($connection, "addEvent", 'INSERT INTO event VALUES (DEFAULT,$0,$1,$2); ');
        // // var_dump(pg_get_result($connection));
        // // pg_send_prepare($connection, "removeEvent", 'DELETE FROM event WHERE id = $1');
        // // var_dump(pg_get_result($connection));
        // // pg_send_prepare($connection, "updateEvent", 'UPDATE event SET title = $2 , description = $3 , type = $4 , date = $5, date_remind = $6 WHERE id = $1 ');
        // var_dump(pg_fetch_all(pg_get_result($connection)));
        // var_dump(pg_get_result($connection));
        /*
        return ExtendedPromise::all([
            $object->dbService->delay(fn()=>$object->dbService->prepare("addEvent", 'INSERT INTO event VALUES ($1,$2,$3,$4,$5) ')),
            $object->dbService->delay(fn()=>$object->dbService->prepare("removeEvent", 'DELETE FROM event WHERE id = $1')),
            // new \Promise(fn($res, $rej) => set_timeout( function() use(&$object,&$res){$object->dbService->prepare("updateEvent", 'UPDATE event SET title = $2 , description = $3 , type = $4 , date = $5, date_remind = $6 WHERE id = $1 ')->then(fn($data)=>$res($data));} ,3000)),
        ])->then(function ($data) use ($object) {
            var_dump($data);*/
            // return \Promise::resolve($object);
//        });
        //-------------------------------------------------------------------------------------------
        $object = new self();
        $db=$object->dbService = self::$containerBuilder->get(DbService::class);
        return new \Promise(function($res,$rej)use(&$object,&$db){
            $db->prepare("addEvent", 'INSERT INTO event VALUES (DEFAULT,$1,$2,$3)')->then(function($data)use(&$object,&$db,$res){
//                $db->prepare("removeEvent", 'DELETE FROM event WHERE id = $1')->then(function($data)use(&$object,&$db,$res){
//                    $db->prepare("updateEvent", 'UPDATE event SET title = $2 , description = $3 , type = $4 , date = $5, date_remind = $6 WHERE id = $1 ')->then(function($data)use(&$object,$res){
                        $res($object);
//                    });
//                });
            });

        });
        //-------------------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------------
        // $object = new self();
        // $db=$object->dbService = self::$containerBuilder->get(DbService::class);
        // return ExtendedPromise::allRecurs([
        //     $db->prepare("addEvent", 'INSERT INTO event VALUES (DEFAULT,$1,$2,$3)'),
        //     $db->prepare("removeEvent", 'DELETE FROM event WHERE id = $1'),
        //     $db->prepare("updateEvent", 'UPDATE event SET title = $2 , description = $3 , type = $4 , date = $5, date_remind = $6 WHERE id = $1 ')

        // ],$object);
        //-----------------------------------------------------------------------------------------
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getDateCreated()
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTime|string $date_created): self
    {
        $this->date_created = is_string($date_created) ? new DateTime($date_created) : $date_created;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTime|string|null $date): self
    {
        $this->date = is_string($date) ? new DateTime($date) : $date;
        return $this;
    }

    public function getDateRemind(): ?\DateTimeInterface
    {
        return $this->date_remind;
    }

    public function setDateRemind(\DateTime|string|null $date_remind): self
    {
        $this->date_remind = is_string($date_remind) ? new DateTime($date_remind) : $date_remind;
        return $this;
    }

    public function getAll()
    {
        // dump($this, json_encode($this));
        $arr = [];
        foreach ($this as $key => $value) {
            if ($key == "dbService") {
                continue;
            }
            $arr [$key] = $value;
        }
        return $arr;
    }

    public function save()
    {
        // var_dump("GET ALL",$this->getAll());
       return $this->dbService->execute("addEvent", $this->getAll());
    }

    public function delete()
    {
        $this->dbService->execute("removeEvent", array("$this->id"));
    }

    public function update()
    {
        $this->dbService->execute("updateEvent", $this->getAll());
    }
}
