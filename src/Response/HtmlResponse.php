<?php
namespace Langivi\ImportantReminder\Response;

class HtmlResponse extends AbstractResponse
{
    public function send($value)
    {
        $this->response->setHeader("Content-Type", "text/html; charset=utf-8");
        $this->response->send($value);
    }
}