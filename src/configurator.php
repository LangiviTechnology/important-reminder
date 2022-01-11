<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function(ContainerConfigurator $configurator) {
//    $configurator->import('services/mailer.php');
    // If you want to import a whole directory:
    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
    ;
    $parameters = $configurator->parameters();

    require_once 'services.php';
    require_once 'parameters.php';

    var_dump($services);
//    $configurator->import('services.php');


};