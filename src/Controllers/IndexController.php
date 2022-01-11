<?php
namespace Langivi\ImportantReminder\Controllers;

use Langivi\ImportantReminder\Loader;
use Langivi\ImportantReminder\Services\TestService;

class IndexController
{
    public function __construct(TestService $controller)
    {
        var_dump("helo", $controller);
    }

    public function index(\HttpRequest $request, \HttpResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("Hello world\n");
    }
}