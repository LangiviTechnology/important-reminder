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
	public static function create(){
		$connectDB = self::$containerBuilder->get('db_connecter')->getConnection();
		$object = new self();
		$object->dbService = self::$containerBuilder->get(DbService::class);
		return ExtendedPromise::all([
		new \Promise(fn($res, $rej) => set_timeout( function() use(&$object,&$res){$object->dbService->prepare("addEvent", 'INSERT INTO event VALUES ($1,$2,$3,$4,$5) ')->then(fn($data)=>$res($data));} ,1000)),
		new \Promise(fn($res, $rej) => set_timeout( function() use(&$object,&$res){$object->dbService->prepare("removeEvent", 'DELETE FROM event WHERE id = $1')->then(fn($data)=>$res($data));} ,2000)),
		new \Promise(fn($res, $rej) => set_timeout( function() use(&$object,&$res){$object->dbService->prepare("updateEvent", 'UPDATE event SET title = $2 , description = $3 , type = $4 , date = $5, date_remind = $6 WHERE id = $1 ')->then(fn($data)=>$res($data));} ,3000)),
		])->then(fn($data)=> \Promise::resolve($object));
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

	public function setDateCreated(\DateTime | string $date_created): self
	{
		$this->date_created = is_string($date_created) ? new DateTime($date_created) : $date_created;
		return $this;
	}

	public function getDate(): ?\DateTimeInterface
	{
		return $this->date;
	}

	public function setDate(\DateTime | string | null $date): self
	{
		$this->date = is_string($date) ? new DateTime($date) : $date;
		return $this;
	}

	public function getDateRemind(): ?\DateTimeInterface
	{
		return $this->date_remind;
	}

	public function setDateRemind(\DateTime | string | null $date_remind): self
	{
		$this->date_remind = is_string($date_remind) ? new DateTime($date_remind) : $date_remind;
		return $this;
	}
	
	public function getAll()
	{
		// dump($this, json_encode($this));
		$arr = [];
		foreach ($this as $key => $value) {
			if ($key == "dbService") {continue;}
			$arr [$key] = $value;
		}
		return $arr;
	}
	public function save ()
	{
		$this->getAll();
		$this->dbService->execute("addEvent",$this->getAll());
	}
	public function delete ()
	{
		$this -> dbService->execute("removeEvent",array("$this->id") );
	}
	public function update ()
	{
		$this->dbService->execute("updateEvent",$this->getAll());
	}
}
