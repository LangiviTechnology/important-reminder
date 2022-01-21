<?php
namespace Langivi\ImportantReminder\Controllers;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Langivi\ImportantReminder\Services\LoggerService;



class IndexController extends AbstractController
{
    public function __construct(
        private LoggerService $logger,
    )
    {
    }

    public function index(\HttpRequest $request, \HttpResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("Index controller: Index\n");
    }
}