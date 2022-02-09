<?php
namespace Langivi\ImportantReminder\Controllers;
// use Symfony\Component\DependencyInjection\ContainerBuilder;

// use Langivi\ImportantReminder\Loader;
use Langivi\ImportantReminder\Services\LoggerService;
use Langivi\ImportantReminder\Response\AbstractResponse;

class EventController extends AbstractController
{
    public function __construct(
        private LoggerService $logger,
    )
    {
    }

    public function findOne(\HttpRequest $request, AbstractResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("EventController: findOne\n");
    }

    public function all(\HttpRequest $request, AbstractResponse $response)
    {
        $twig = $this->containerBuilder->get('twig');
        $response->setHeader("Content-Type", "text/html; charset=utf-8");
        $response->send($twig->render('events-all.twig', ['title' => 'Login']));
        return;
    }

	public function add(\HttpRequest $request, AbstractResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("EventController: add\n");
        $this->logger->info('Add event');

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