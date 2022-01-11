<?php
namespace Langivi\ImportantReminder\Controllers;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Langivi\ImportantReminder\Loader;
use Langivi\ImportantReminder\Services\TestService;

class IndexController
{
    private readonly ContainerBuilder $containerBuilder;

    public function __construct(TestService $controller)
    {

    }

    public function setContainer(ContainerBuilder $container): self
    {
        $this->container = $container;
        return $this;
    }

    public function index(\HttpRequest $request, \HttpResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("Hello world\n");
    }
}