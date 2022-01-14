<?php
namespace Langivi\ImportantReminder\Controllers;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Langivi\ImportantReminder\Services\LoggerService;



class IndexController
{
    private readonly ContainerBuilder $containerBuilder;
    public function __construct(
        private LoggerService $logger,
    )
    {
    }

    public function setContainer(ContainerBuilder $container): self
    {
        $this->container = $container;
        return $this;
    }

    public function index(\HttpRequest $request, \HttpResponse $response)
    {
        $this->logger->info('get index');
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("Index controller: Index\n");
    }
}