<?php
namespace Langivi\ImportantReminder\Response;
abstract class AbstractResponse
{
    public function __construct(private \HttpResponse $response)
    {
    }

    abstract public function send();
}