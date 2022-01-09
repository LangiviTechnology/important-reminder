<?php

namespace Langivi\ImportantReminder\Routing;

class Route
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var array<string>
     */
    private $methods = [];

    /**
     * @var array<string>
     */
    private $vars = [];

    public static function create(string $path, string $controller, string $name = '', array $methods = ['GET'], array $vars = [])
    {
        return new self($name, $path, $controller, $methods, $vars);
    }

    private function __construct(string $name, string $path, string $controller, array $methods = ['GET'], array $vars = [])
    {
        // TODO is need chech exist at least one method?
        $this->name = $name;
        $this->path = $path;
        $this->controller = $controller;
        $this->methods = $methods;
        $this->vars = $vars;
    }

    public function call(\HttpRequest $request, \HttpResponse $response)
    {
        [$controller, $action] = explode("::", $this->controller);
        $controllerClass = new $controller; //TODO rewrite to DI inject;
        $controllerClass->{$action}($request, $response);
    }

    public function match(string $uri, string $method): bool
    {
        $requestPath = strtolower(trim($uri, '{\}'));
        $routePath = strtolower(trim($this->path, '{\}'));
        if (strcmp($requestPath, $routePath) !== 0) {
            return false;
        }

        if (!in_array($method, $this->getMethods())) {
            return false;
        }

        return true;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getController():string
    {
        return $this->controller;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getVars(): array
    {
        return $this->vars;
    }

    public function hasVars(): bool
    {
        return boolval($this->vars);
    }
}