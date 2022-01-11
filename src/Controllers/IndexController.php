<?php
namespace Langivi\ImportantReminder\Controllers;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class IndexController
{
    private readonly ContainerBuilder $containerBuilder;

    public function __construct(ContainerBuilder $container)
    {
        $this->containerBuilder = $container;
    }   

    public function index(\HttpRequest $request, \HttpResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("Hello world\n");
    }
}