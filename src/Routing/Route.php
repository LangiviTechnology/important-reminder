<?php

namespace Langivi\ImportantReminder\Routing;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use ArrayObject;


class Route
{
    private readonly ContainerBuilder $container;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $path;


    private \Closure | string  $controller;

    /**
     * @var array<HttpMethods>
     */
    private array $methods = [];

    /**
     * @var array<string>
     */
    private array $vars = [];

    /**
	 * @var ArrayObject
	 */
	private ArrayObject $middleWares = [];


    public static function create(string $path, callable | string $controller, string $name = '', array $methods = [HttpMethods::GET], array $vars = [])
    {
        return new self($name, $path, $controller, $methods, $vars);
    }

    private function __construct(string $name, string $path, callable | string  $controller, array $methods = [HttpMethods::GET], array $vars = [])
    {
        // TODO is need check exist at least one method?
        $this->name = $name;
        $this->path = $path;
        $this->controller = $controller;
        $this->methods = $methods;
        $this->vars = $vars;
    }

    public function call(\HttpRequest $request, \HttpResponse $response)
    {
        $middleRequest = $request;
        $middleResponse = $response;
        if (count($this->middleWares)) {
            foreach ($this->middleWares as $middleWare) {
                [$middleRequest, $middleResponse] = $middleWare($request, $response);
            }
        }
        $this->call($request, $response);

        // $controllerClass = new ('\Langivi\ImportantReminder\Controllers\\' . $controller); //TODO rewrite to DI inject;
        // $controllerClass->{$action}($request, $response);
    }

    public function execute(\HttpRequest $request, \HttpResponse $response)
    {
        if (is_string($this->controller)){
            [$controllerName, $action] = explode("::", $this->controller);
            if ($this->container) $controller = $this->container->get($controllerName);
            $controller->{$action}($request, $response);
        } elseif ($this->controller instanceof \Closure){
            ($this->controller)($request, $response);
        }

        // $controllerClass = new ('\Langivi\ImportantReminder\Controllers\\' . $controller); //TODO rewrite to DI inject;
        // $controllerClass->{$action}($request, $response);
    }

    public function match(string $uri, HttpMethods $method): bool
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

    public function getController(): string
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

    public function setContainer(ContainerBuilder $container): self
    {
        $this->container = $container;
        return $this;
    }
}