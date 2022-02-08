<?php
namespace Langivi\ImportantReminder\Response;

class JsonResponse extends AbstractResponse
{
    public function prepare(string $value)
    {
        $this->response->setHeader("Content-Type", "application/json");
        return json_encode($value);
    }

}