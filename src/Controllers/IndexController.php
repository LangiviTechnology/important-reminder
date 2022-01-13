<?php
namespace Langivi\ImportantReminder\Controllers;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Langivi\ImportantReminder\Loader;
use Langivi\ImportantReminder\Services\EventService;
use Langivi\ImportantReminder\Services\TestService;
use Langivi\ImportantReminder\Controllers\AbstractController;

class IndexController extends AbstractController
{
    private readonly ContainerBuilder $containerBuilder;
    public function __construct(
        private TestService $controller, EventService $eventService
    )
    {
    }

    // public function setContainer(ContainerBuilder $container): self
    // {
    //     $this->container = $container;
    //     return $this;
    // }

    public function index(\HttpRequest $request, \HttpResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("Index controller: Index\n");
    }
}