#!/usr/bin/env php
<?php
$httpServer = new HttpServer(81, "tcp://0.0.0.0");
$httpServer->setPublicPath(__DIR__ . DIRECTORY_SEPARATOR . "public");
$result = new finfo();
$httpServer->on_request(function (HttpRequest $req, HttpResponse $res) use ($result) {
    if (file_exists($this->publicPath . $req->uri) && !is_dir($this->publicPath . $req->uri)) {
        $mimeType = $result->file($this->publicPath . $this->uri, FILEINFO_MIME_TYPE);
        $file = file_get_contents($this->publicPath . $req->uri);
        echo "Requested URI is $this->publicPath.$req->uri\n";
        $res->setHeader("Content-Type", $mimeType)
            ->setHeader("Content-Length", strlen($file));
        $res->send($file);

        return;
    }
    $res->setHeader("Content-Type", "text/plain; charset=utf-8");
    $res->send("Hello world\n");

});
