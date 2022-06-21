<?php
namespace Langivi\ImportantReminder\Controllers;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use  Langivi\ImportantReminder\Entity\Event;
use Langivi\ImportantReminder\Connectors\DBConnector;
use Langivi\ImportantReminder\Loader;
use Langivi\ImportantReminder\Services\LoggerService;
use Langivi\ImportantReminder\Response\AbstractResponse;

class EventController extends AbstractController
{
    public function __construct(
        private LoggerService $logger
    )
    {
    }

    public function findOne(\HttpRequest $request, AbstractResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("EventController: findOne\n");
    }

    public function all(\HttpRequest $request, \HttpResponse $response)
    {  
        //  $eve = Event::create()->then(fn($data)=>var_dump("data in eventController",$data)); працює

        Event::create()->then(function(Event $event) use(&$response){
             $event->setTitle(' sasdddddd ');
             $event->setDescription('description');
             $event->setType('nowType');
             $event->save();
            $response->setHeader("Content-Type", "text/plain; charset=utf-8");
            $response->send("EventController: all\n");
            });
//        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
//        $response->send("EventController: all\n");

            // $dbSe = $this->containerBuilder->get('db_service');
            // $dbSe->prepare("addEvent", 'INSERT INTO event VALUES (DEFAULT,$1,$2,$3) ')->then(function($data)use(&$response,&$dbSe){
            //    var_dump("DAAAAAAAAATAAAA",$data);
            //    $getEX=$dbSe->execute("addEvent", array('SUPER TEPER TUT','sdasdads','asdasd'));
            //    var_dump($getEX);
            //    $response->setHeader("Content-Type", "text/plain; charset=utf-8");
            //    $response->send("EventController: all\n");

            // });
       
       
    }

	public function add(\HttpRequest $request, AbstractResponse $response)
    {
        // $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        // $response->send("EventController: add\n");
        // $this->logger->info('Add event');
        // $eve = new Event();
        // $sd = $eve->save();
        //  Event::create()->then(function($data){var_dump($data);});
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("EventController: add\n");

    }

	public function update(\HttpRequest $request, AbstractResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("EventController: update\n");
    }

	public function delete(\HttpRequest $request, AbstractResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("EventController: delete\n");
    }

}