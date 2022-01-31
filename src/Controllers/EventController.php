<?php
namespace Langivi\ImportantReminder\Controllers;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use  Langivi\ImportantReminder\Entity\Event;
use Langivi\ImportantReminder\Connectors\DBConnector;
use Langivi\ImportantReminder\Loader;
use Langivi\ImportantReminder\Services\LoggerService;

class EventController extends AbstractController
{
    public function __construct(
        private LoggerService $logger
    )
    {
    }

    public function findOne(\HttpRequest $request, \HttpResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("EventController: findOne\n");
    }

    public function all(\HttpRequest $request, \HttpResponse $response)
    {  
        //  $eve = Event::create()->then(fn($data)=>var_dump("data in eventController",$data)); працює

        Event::create()
            ->then(function(Event $event) use(&$response){
             $event->setTitle('1sds Title');
             $event->setDate('1223');
             $event->setDateCreated('123213');
             $event->setDescription('description');
             $event->setType('nowType');
             $event->setDateCreated('232532');
             $event->save();
             $response->setHeader("Content-Type", "text/plain; charset=utf-8");
             $response->send("EventController: all\n");
             return false;
         });
//        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
//        $response->send("EventController: all\n");
    }

	public function add(\HttpRequest $request, \HttpResponse $response)
    {
        // $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        // $response->send("EventController: add\n");
        // $this->logger->info('Add event');
        // $eve = new Event();
        // $sd = $eve->save();
         Event::create()->then(function($data){var_dump($data);});
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("EventController: add\n");

    }

	public function update(\HttpRequest $request, \HttpResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("EventController: update\n");
    }

	public function delete(\HttpRequest $request, \HttpResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("EventController: delete\n");
    }

}