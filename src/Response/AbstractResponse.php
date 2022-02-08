<?php
namespace Langivi\ImportantReminder\Response;
abstract class AbstractResponse
{
    public function __construct(public \HttpResponse $response)
    {
    }

    abstract protected function prepare(string $value);

    abstract protected function error(array $value);

    public function send(string $value){
        return $this->response->send($this->prepare($value));
    }

    function __call($name, $args) {
        $r = new \ReflectionClass($this->response);
        if ($method = $r->getMethod($name)) {
            if ($method->isPublic() && !$method->isAbstract()) {
                return $method->invoke($this->response, ...$args);
            }
        }
    }
}