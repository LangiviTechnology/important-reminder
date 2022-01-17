<?php
/** @var Symfony\Component\DependencyInjection\Loader\Configurator\ParametersConfigurator $parameters */
$parameters->set('env', 'dev');
$env =file(".env");
foreach($env as $param){
    if(str_starts_with($param,'#')){
        continue;
    }
    [$key,$value]=explode("=",$param);
    $value = trim(str_replace("\"","",$value));
    var_dump($key,$value);
    $parameters->set($key,$value);
   
}
