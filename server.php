#!/usr/bin/env php
<?php


use Langivi\ImportantReminder\Loader;

require 'vendor/autoload.php';

echo "hello";
$loader = Loader::boot();
$httpServer = new HttpServer(81, "tcp://0.0.0.0");
$httpServer->setPublicPath(__DIR__ . DIRECTORY_SEPARATOR . "public");
$result = new finfo();

function servePublic(string $path, HttpResponse $res, finfo $fileinfo): void
{
    $mimeType = $fileinfo->file($path, FILEINFO_MIME_TYPE);
    $file = file_get_contents($path);
    echo "Requested URI is $path\n";
    $res->setHeader("Content-Type", $mimeType)
        ->setHeader("Content-Length", strlen($file));
    $res->send($file);
}

$httpServer->on_request(function (HttpRequest $req, HttpResponse $res) use ($result) {
    global $loader;
    var_dump($req);
    $publicUri = $this->publicPath . $req->uri;
    if (file_exists($publicUri) && !is_dir($publicUri)) {
        servePublic($publicUri, $res, $result);
        return;
    }
    $res->setHeader("Content-Type", "text/plain; charset=utf-8");
    $res->send("Hello world\n");

});
