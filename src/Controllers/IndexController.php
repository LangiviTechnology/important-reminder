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
        $twig = $this->containerBuilder->get('twig');

        $response->setHeader("Content-Type", "text/html; charset=utf-8");
        $response->send($twig->render('index.twig', ['title' => 'Reminder']));
    }
}