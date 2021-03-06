<?php

namespace Langivi\ImportantReminder;


use Langivi\ImportantReminder\Migrations\Migration;
use Langivi\ImportantReminder\Connectors\DBConnector;
use Langivi\ImportantReminder\Services\DbService;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\{ContainerBuilder, Loader\PhpFileLoader};
use Langivi\ImportantReminder\Routing\Router;
use Langivi\ImportantReminder\Services\LoggerService;
use Langivi\ImportantReminder\Entity\AbstractEntity;
use Langivi\ImportantReminder\Services\TokenService;


class Loader
{
    private readonly ContainerBuilder $containerBuilder;
    private static self $instance;

    public static function get(): ?self
    {
        return self::$instance;
    }

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
            // 'cache' => __DIR__ . '/cache/twig_cache',
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

    public function registerMigrations()
    {
        $this->containerBuilder->register(Migration::class, Migration::class)
            ->setAutowired(true)->setPublic(true);
    }

    public function injectServices(): self
    {
        echo 'Inject Services' . PHP_EOL;
        $loader = new PhpFileLoader($this->containerBuilder, new FileLocator(__DIR__));
        $loader->load('config/configurator.php');
        return $this;
    }
    public function injectEntity ()
    {   
        AbstractEntity::setContainer($this->containerBuilder);
        
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

    public function injectServiceDB(): self
    {
        $dbHost = $this->containerBuilder->getParameter('DB_HOST');
        $dbName = $this->containerBuilder->getParameter('DB_NAME');
        $dbUser = $this->containerBuilder->getParameter('DB_USER');
        $dbPassword = $this->containerBuilder->getParameter('DB_PASSWORD');
        $this->containerBuilder->set('db_connecter', new DBConnector(
            $dbHost, $dbName, $dbUser, $dbPassword
        ));
        DbService::setContainer($this->containerBuilder);
        $this->containerBuilder->set('db_service',new DbService());
        return $this;
    }

    public function setupLogger()
    {
        echo 'Setup Logger' . PHP_EOL;
        $logFileName = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log';
        /**
         * @var $logger LoggerService
         */
        $logger = $this->containerBuilder->get(LoggerService::class);
        $logger->getHandler()->setFileName($logFileName);
        $logger->setMode('DEBUG');
        return $this;
    }

    public function setupTokenService()
    {
        echo 'Setup Token service' . PHP_EOL;
        $tokenService = $this->containerBuilder->get(TokenService::class);
        $tokenService->setAccessToken(
            $this->containerBuilder->getParameter('JWT_ACCESS_SECRET')
        );
        $tokenService->setRefreshToken(
            $this->containerBuilder->getParameter('JWT_REFRESH_SECRET')
        );
        return $this;
    }

    public function getContainer()
    {
        return $this->containerBuilder;
    }

    public static function bootCli()
    {
        $object = new self();
        $object->containerBuilder->set(Loader::class, $object);
        $object->injectServices()
            ->injectServiceDB()
            ->registerMigrations();
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
            ->injectServiceDB()
            ->injectEntity();
        $object->containerBuilder->compile();
        $object->setupLogger();
        $object->setupTokenService();
        $object->containerBuilder->get(LoggerService::class)->info('Server started');
        // var_dump($object->containerBuilder->get(IndexController::class));
//        var_dump($object->containerBuilder->getParameter('env'));
//        var_dump($object->containerBuilder->getServiceIds());
        self::$instance = $object;
        return $object;
    }
}