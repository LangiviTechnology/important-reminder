<?php
namespace Langivi\ImportantReminder\Controllers;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Langivi\ImportantReminder\Loader;
use Langivi\ImportantReminder\Services\UserService;
use Langivi\ImportantReminder\Controllers\AbstractController;

class AuthController extends AbstractController
{
    private readonly ContainerBuilder $containerBuilder;
    public function __construct(
        private UserService $userService,
    )
    {
    }

    // public function setContainer(ContainerBuilder $container): self
    // {
    //     $this->container = $container;
    //     return $this;
    // }

    public function register(\HttpRequest $request, \HttpResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("register");
    }

	public function login(\HttpRequest $request, \HttpResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("login");
    }

	public function logout(\HttpRequest $request, \HttpResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("logout");
    }
}