<?php
namespace Langivi\ImportantReminder\Controllers;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Langivi\ImportantReminder\Loader;
use Langivi\ImportantReminder\Services\TestService;

class EventController
{
    private readonly ContainerBuilder $containerBuilder;
    public function __construct(
        private EventService $controller,
    )
    {
    }

    public function setContainer(ContainerBuilder $container): self
    {
        $this->container = $container;
        return $this;
    }

    public function findOne(\HttpRequest $request, \HttpResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("EventController: findOne\n");
    }

    public function all(\HttpRequest $request, \HttpResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("EventController: all\n");
    }

	public function add(\HttpRequest $request, \HttpResponse $response)
    {
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