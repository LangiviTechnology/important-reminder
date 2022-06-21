<?php
namespace Langivi\ImportantReminder\Controllers;

use Langivi\ImportantReminder\Services\LoggerService;
use Langivi\ImportantReminder\Response\AbstractResponse;

class IndexController extends AbstractController
{
    public function __construct(
        private LoggerService $logger,
    )
    {
    }

    public function index(\HttpRequest $request, AbstractResponse $response)
    {

        $response->setTemplate('index.twig');
        $response->send(['title' => 'Reminder']);
    }
}