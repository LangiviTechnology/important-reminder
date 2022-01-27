<?php 

namespace Langivi\ImportantReminder\Services;

use  Langivi\ImportantReminder\Entity\Event;

class EventService
{
	public function __construct(private DbService $dBService)
	{
	}

    public function add(Event $eventDto): Event
    {
        $this->dBService->execute("addEvent",['title','decriptttion','neveType','123.231.212','12321,423423']);
    }

	public function findOne(Event $eventDto): Event
    {

    }

	public function findAll(): array
    {

    }

	public function update(Event $eventDto): Event
    {

    }

	public function delete(Event $eventDto): bool
    {

    }


}
