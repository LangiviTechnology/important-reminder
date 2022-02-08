<?php

namespace Langivi\ImportantReminder\Routing;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Langivi\ImportantReminder\MiddleWares\AuthMiddleware;
use Langivi\ImportantReminder\Handlers\ExceptionHandler;
use Langivi\ImportantReminder\Response\AbstractResponse;
use Exception;

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

    private bool $isRequireAuth;

    public static function create(
        string $path, 
        callable | string $controller, 
        string $name = '', 
        array $methods = [HttpMethods::GET], 
        array $vars = [],
        bool $isRequireAuth = false
        )
    {
        return new self($name, $path, $controller, $methods, $vars, $isRequireAuth);
    }

    private function __construct(
        string $name, 
        string $path, 
        callable | string  $controller, 
        array $methods = [HttpMethods::GET], 
        array $vars = [],
        bool  $isRequireAuth = false
        )
    {
        // TODO is need check exist at least one method?
        $this->name = $name;
        $this->path = $path;
        $this->controller = $controller;
        $this->methods = $methods;
        $this->vars = $vars;
        $this->isRequireAuth = $isRequireAuth;
    }

    public function call(\HttpRequest $request, AbstractResponse $response)
    {
        try {
            if ($this->isRequireAuth) {
                $auth = $this->container->get(AuthMiddleware::class);
                if (!$auth->middleware($request, $response)) {
                    throw new Exception('Unauthorized', 401);
                }
            }
            $this->execute($request, $response);
        } catch (\Throwable $th) {
            $exceptionHandler = $this->container->get(ExceptionHandler::class);
            $exceptionHandler->sendError($response, $th->getMessage(), $th->getCode(), ['path'=> $request->uri]);
        }
    }

    public function execute(\HttpRequest $request, AbstractResponse $response)
    {
        if (is_string($this->controller)){
            [$controllerName, $action] = explode("::", $this->controller);
            if ($this->container) $controller = $this->container->get($controllerName);
            $controller->{$action}($request, $response);
        } elseif ($this->controller instanceof \Closure){
            ($this->controller)($request, $response);
        }
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