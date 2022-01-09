<?php

namespace Langivi\ImportantReminder;

use Langivi\ImportantReminder\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Loader
{
    private readonly ContainerBuilder $containerBuilder;

    public function __construct()
    {
        $this->containerBuilder = new ContainerBuilder();
    }

    public function setTemplateEngine()
    {
        if (!is_dir(__DIR__ . '/templates')) {
            mkdir(__DIR__ . '/templates');
        }
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');

        $twig = new \Twig\Environment($loader, [
            'cache' => __DIR__ . '/cache/twig_cache',
        ]);
        $this->containerBuilder->set('twig', $twig);
    }

    public function setRouter()
    {
        $routes = require_once './Routing/routes.php';
        $this->containerBuilder->set('router', new Router($routes));
    }

    public function getContainer()
    {
        return $this->containerBuilder;
    }

    public static function boot()
    {
        $object = new self();
        $object->setTemplateEngine();
        $object->setRouter();
        return $object;
    }
}