<?php
namespace Langivi\ImportantReminder\Response;
abstract class AbstractResponse
{
    public function __construct(public \HttpResponse $response)
    {
    }

    abstract public function send($value);

    function __call($name, $args) {
        $r = new \ReflectionClass($this->response);
        if ($method = $r->getMethod($name)) {
            if ($method->isPublic() && !$method->isAbstract()) {
                return $method->invoke($this->response, ...$args);
            }
        }
    }
}