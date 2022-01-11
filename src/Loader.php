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

    public function injectControllers()
    {
        echo 'Inject controllers:' . PHP_EOL;
        foreach (glob(__DIR__ . '/Controllers/*.php') as $file)
        {
            // $c = require_once $file;
            $controller = basename($file, '.php');
            echo $controller . PHP_EOL;
            
            $reflector = new \ReflectionClass(('\Langivi\ImportantReminder\Controllers\\' . $controller));

            if (!$reflector->isInstantiable()) {
                throw new Exception("Class is not instantiable");
            }
    
            $constructor = $reflector->getConstructor();
            if (is_null($constructor)) {
                continue;
            }

            $parametrs = $constructor->getParameters();
            // var_dump($parametrs[0]->getType());
            $this->containerBuilder->setParameter('cont', 89569);
            $this->containerBuilder->register($controller, ('\Langivi\ImportantReminder\Controllers\\' . $controller))->addArgument($this->containerBuilder);

            // var_dump($this->containerBuilder->get($controller));
        }  
    }

    public function setRouter()
    {
        $routes = require_once __DIR__ . '/Routing/routes.php';
        $this->containerBuilder->set('router', new Router($routes, $this->containerBuilder));
    }

    public function getContainer()
    {
        return $this->containerBuilder;
    }

    public static function boot()
    {
        $object = new self();
        $object->setTemplateEngine();
        $object->injectControllers();
        $object->setRouter();
        return $object;
    }
}