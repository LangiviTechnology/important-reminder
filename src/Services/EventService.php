<?php 

namespace Langivi\ImportantReminder\Services;

use  Langivi\ImportantReminder\Entity\Event;

class EventService
{
	public function __construct(private DBService $dBService)
	{
        $dBService->connectDB();
	}

    public function add(Event $eventDto): Event
    {

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
