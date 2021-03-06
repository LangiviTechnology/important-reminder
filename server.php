#!/usr/bin/env php
<?php

use Langivi\ImportantReminder\Loader;
use Langivi\ImportantReminder\Routing\HttpMethods;

// use Langivi\ImportantReminder\Services\LoggerService;
use Langivi\ImportantReminder\Handlers\ExceptionHandler;
use Langivi\ImportantReminder\Response\HtmlResponse;
use Langivi\ImportantReminder\Response\JsonResponse;

use Langivi\ImportantReminder\Routing\Router;
use function Langivi\ImportantReminder\Utils\getFileMimeType;

require 'vendor/autoload.php';
require_once 'src/Utils/MimeType.php';

const PORT = 81;
echo "Server is starting ..." . PHP_EOL;
$loader = Loader::boot();
$httpServer = new HttpServer(PORT, "tcp://0.0.0.0");
$httpServer->setPublicPath(__DIR__ . DIRECTORY_SEPARATOR . "public");
$result = new finfo();
echo "Started on PORT " . PORT . PHP_EOL;

function servePublic(string $path, HttpResponse $res, finfo $fileinfo): void
{
    $mimeType = getFileMimeType($path);
    $res->setHeader("Content-Type", $mimeType);
    file_get_contents_async($path, //TODO find an excessive \0 symbol
        fn(string $arg): HttpResponse|null => $res->setHeader("Content-Length", strlen($arg)-1)->send($arg));
    // FIX PROBLEM WITH ASYNC READ
    echo "Requested URI is $path" . PHP_EOL;
}

$httpServer->on_request(function (HttpRequest $req, HttpResponse $res) use ($result) {
    global $loader;

    $response = match ($req->headers["Sec-Fetch-Mode"]) {
        'no-cors' =>  new JsonResponse($res),
        'cors' =>  new JsonResponse($res),
        default => new HtmlResponse($res),
    };
    
    // $logger = $loader->getContainer()->get(LoggerService::class);
    //    file_get_contents_async('server.php', fn($arg)=>var_dump($arg));
    try {
        $publicUri = $this->publicPath . $req->uri;
        if (file_exists($publicUri) && !is_dir($publicUri)) {
            servePublic($publicUri, $res, $result);
            return;
        }
        /**
         * @var $router Router
         */
        $router = $loader->getContainer()->get('router');
        $method = HttpMethods::tryFrom($req->method);

        if (!$method) {
            // TODO: rewrite to appropriate exception classes
            throw new Exception('Incorrect method' . $req->method, 404);
        }

        $route = $router->matchFromPath($req->uri, $method);
        if (!$route) {
            // TODO: rewrite to appropriate exception classes
            throw new Exception('Path not found ' . $req->uri, 404);
        }
        $route->call($req, $response);

    } catch (\Throwable $th) {
        $exceptionHandler = $loader->getContainer()->get(ExceptionHandler::class);
        $exceptionHandler->sendError($response, $th->getMessage(), $th->getCode(), ['uri' => $req->uri, 'method' => $method->value]);
    }

});

$httpServer->on_error(function ($error) {
    echo 'error=================';
    var_dump($error);
    // $logger->error('', $error);
});