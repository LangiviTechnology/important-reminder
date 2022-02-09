<?php
namespace Langivi\ImportantReminder\Response;

class JsonResponse extends AbstractResponse
{
    public function prepare(array|object $value)
    {
        $this->response->setHeader("Content-Type", "application/json");
        return json_encode($value);
    }
    public function error(array|object $value)
    {
        $this->send($value);
    }
}