<?php
namespace Langivi\ImportantReminder\Controllers;

class IndexController
{
    public function index(\HttpRequest $request, \HttpResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("Hello world\n");
    }
}