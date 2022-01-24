<?php

namespace Langivi\ImportantReminder;

use Langivi\ImportantReminder\Controllers\IndexController;
use Langivi\ImportantReminder\Migrations\Migration;
use Langivi\ImportantReminder\Routing\Router;
use Langivi\ImportantReminder\Services\DBConnecter;
use Langivi\ImportantReminder\Services\DBService;
use Langivi\ImportantReminder\Services\TestService;
use Symfony\Component\Config\FileLocator;
use Langivi\ImportantReminder\Utils\LoggerHandler;
use Langivi\ImportantReminder\Services\LoggerService;
use Langivi\ImportantReminder\Services\MessageGenerator;
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
        echo 'Set template' . PHP_EOL;
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
        foreach (glob(__DIR__ . '/Controllers/*.php') as $file) {
            $controller = basename($file, '.php');
            echo ' - ' . $controller . PHP_EOL;
            $reflector = new \ReflectionClass(('\Langivi\ImportantReminder\Controllers\\' . $controller));
            if ($reflector->isAbstract())
                continue;
            $definition = $this->containerBuilder->register($reflector->getShortName(), $reflector->getName())
                ->setAutowired(true)->setPublic(true);
            $definition->addMethodCall('setContainer', [$this->containerBuilder]);
            $this->containerBuilder->setAlias($reflector->getName(), $reflector->getShortName(),)->setPublic(true);

            if (!$reflector->isInstantiable()) {
                throw new \Exception("Class is not instantiable");
            }
        }
        return $this;
    }

    public function injectServices()
    {
        echo 'Inject Services' . PHP_EOL;
        $loader = new PhpFileLoader($this->containerBuilder, new FileLocator(__DIR__));
        $loader->load('configurator.php');
        return $this;
    }

    public function setRouter()
    {
        echo 'Set router' . PHP_EOL;
        Router::setContainer($this->containerBuilder);
        $routes = require_once __DIR__ . '/Routing/routes.php';
        $router = new Router($routes);
        $this->containerBuilder->set('router', $router);
        return $this;
    }
    public function injectServiceDB()
    {   DBConnecter::setContainer($this->containerBuilder);
        DBService::setContainer($this->containerBuilder);
        $dbConnecter = new DBConnecter();
        $this->containerBuilder->set('dbconnecter', $dbConnecter);
    }

    public function setupLogger()
    {
        echo 'Setup Logger' . PHP_EOL;
        $logFileName = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log';
        $logHandler = new LoggerHandler();
        $logHandler->setFileName($logFileName);
        $logger = $this->containerBuilder->get(LoggerService::class);
        $logger->setHandler($logHandler);
        $logger->setMode('DEBUG');
        return $this;
    }

    public function getContainer()
    {
        return $this->containerBuilder;
    }
    public static function bootCli(){
        $object = new self();
        $object->containerBuilder->set(Loader::class, $object);
        $object->injectServices()
                ->injectServiceDB();
        $object->containerBuilder->compile();
        return $object;
    }
    public static function boot()
    {
        $object = new self();
        $object->setTemplateEngine();
        $object->containerBuilder->set(Loader::class, $object);
        $object->injectServices()
            ->injectControllers()
            ->setRouter()
            ->injectServiceDB();
        $object->containerBuilder->compile();
        $object->setupLogger();
        $object->containerBuilder->get(LoggerService::class)->info('Server started');
        // var_dump($object->containerBuilder->get(IndexController::class));
//        var_dump($object->containerBuilder->getParameter('env'));
//        var_dump($object->containerBuilder->getServiceIds());

        return $object;
    }
}