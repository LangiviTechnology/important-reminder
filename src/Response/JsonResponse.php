<?php
namespace Langivi\ImportantReminder\Response;

class JsonResponse extends AbstractResponse
{

    public function send($value)
    {
        $this->response->setHeader("Content-Type", "application/json");
        $this->response->send($value);
    }
}