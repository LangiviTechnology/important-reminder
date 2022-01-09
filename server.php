#!/usr/bin/env php
<?php

use Langivi\ImportantReminder\Loader;
use Langivi\ImportantReminder\Routing\HttpMethods;
use Langivi\ImportantReminder\Routing\Router;

require 'vendor/autoload.php';

echo "hello";
$loader = Loader::boot();
$httpServer = new HttpServer(81, "tcp://0.0.0.0");
$httpServer->setPublicPath(__DIR__ . DIRECTORY_SEPARATOR . "public");
$result = new finfo();


function servePublic(string $path, HttpResponse $res, finfo $fileinfo): void
{
    $mimeType = $fileinfo->file($path, FILEINFO_MIME_TYPE);
    $res->setHeader("Content-Type", $mimeType);
    $file = file_get_contents_async($path, fn($arg)=>var_dump($arg)&$res->setHeader("Content-Length", strlen($arg))
        ->send($arg)); // FIX PROBLEM WITH ASYNC READ
    echo "Requested URI is $path\n";
}

$httpServer->on_request(function (HttpRequest $req, HttpResponse $res) use ($result) {
    global $loader;
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
    $action = $router->matchFromPath($req->uri, HttpMethods::from($req->method));
    $action->call($req, $res);

});
