<?php

namespace Langivi\ImportantReminder;

use Langivi\ImportantReminder\Controllers\IndexController;
use Langivi\ImportantReminder\Routing\Router;
use Langivi\ImportantReminder\Services\TestService;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;


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

    public function injectControllers()
    {

        $this->containerBuilder->register('index_controller', IndexController::class)
            ->setAutowired(true)->setPublic(true);
        $this->containerBuilder->setAlias(IndexController::class, 'index_controller',)->setPublic(true);


//        $this->containerBuilder->set( Loader::class,)->addTag('loader')->setAutowired(true)->setPublic(true);

//        $this->containerBuilder->register( IndexController::class, IndexController::class)->addTag('index_controller')->setAutoconfigured(true)->setAutowired(true);
//        $this->containerBuilder->autowire('loader',Loader::class);
//        $this->containerBuilder->compile();
//        var_dump($loader);


//        var_dump($this->containerBuilder);
//        foreach (glob('\Langivi\ImportantReminder\Controllers\*.php') as $file)
//        {
//            require_once $file;
//            $controller = basename($file, '.php');
//            $reflector = new ReflectionClass($controller);
//            $parametrs = $constructor->getParameters();
//
//            $this->containerBuilder->set($controller, new $controller($dependencies));
//        }
    }

    public function injectServices()
    {
        $loader = new PhpFileLoader($this->containerBuilder, new FileLocator(__DIR__));
        $loader->load('configurator.php');

    }

    public function setRouter()
    {
        $routes = require_once __DIR__ . '/Routing/routes.php';
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
        $object->injectServices();
        $object->injectControllers();
        $object->containerBuilder->compile();
        var_dump($object->containerBuilder->get('index_controller'));
        var_dump($object->containerBuilder->getParameter('env'));
        var_dump($object->containerBuilder->getServiceIds());
        $object->setRouter();
        return $object;
    }
}