#!/usr/bin/env php
<?php

use Langivi\ImportantReminder\Loader;
use Langivi\ImportantReminder\Routing\HttpMethods;
// use Langivi\ImportantReminder\Routing\Router;
use Langivi\ImportantReminder\Services\LoggerService;
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
    // $mimeType = $fileinfo->file($path, FILEINFO_MIME_TYPE);
    $mimeType = getFileMimeType($path);
    $res->setHeader("Content-Type", $mimeType);
    var_dump($mimeType);
    $file = file_get_contents_async($path, fn($arg)=>var_dump($arg)&$res->setHeader("Content-Length", strlen($arg))
        ->send($arg)); // FIX PROBLEM WITH ASYNC READ
    echo "Requested URI is $path\n";
}

$httpServer->on_request(function (HttpRequest $req, HttpResponse $res) use ($result) {
    global $loader;
    $logger = $loader->getContainer()->get(LoggerService::class);
//    var_dump($req);
//    file_get_contents_async('server.php', fn($arg)=>var_dump($arg));
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

    // TODO: Add Main error handler
    if (!$method) {
        $res->setStatusCode(404);
        $res->send('Incorrect method' . $req->method);
        $logger->error('Incorrect method ' . $req->method);
        return;
    }
    
    $route = $router->matchFromPath($req->uri, $method);
    if (!$route) {
        $res->setStatusCode(404);
        $res->send('Path not found ' . $req->uri);
        $logger->warning('Path not found: ', ['uri' => $req->uri, 'method' => $method->value]);
        return;
    }
    // throw new Error('test error');
    $route->call($req, $res);

});

$httpServer->on_error(function ($error){
    // global $loader;
    // $logger = $loader->getContainer()->get(LoggerService::class);
    // echo 'error=================';
    var_dump($error);
    // $logger->error('', $error);
});