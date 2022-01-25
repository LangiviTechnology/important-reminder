<?php

namespace App\Entity;

use DateTime;
use Langivi\ImportantReminder\Connectors\DBConnector;

class Event
{	
	private int $id;

	private string $title;

	private string $description;

	private string $type;

	private DateTime $date;

	private DateTime $date_remind;

	private DateTime $date_created;

	public function __construct(DBConnector $connecter)
	{	$connectDB = $connecter->getConnection();
        pg_send_prepare($connectDB, "addEvent", 'INSERT INTO event VALUES ($1,$2,$3,$4,$5) ');
        pg_send_prepare($connectDB, "removeEvent", 'DELETE FROM event WHERE id = $1' );
        pg_send_prepare($connectDB, "updateEvent", 'UPDATE event SET title = $2 , description = $3 , type = $4 , date = $5, date_remind = $6 WHERE id = $1 ');
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
	
	// public function getJson()
	// {
	// 	// dump($this, json_encode($this));
	// 	$arr = [];
	// 	foreach ($this as $key => $value) {
	// 		$arr [$key] = $value;
	// 	}
	// 	return $arr;
	// }
}
