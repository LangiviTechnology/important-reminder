<?php
/** @var Symfony\Component\DependencyInjection\Loader\Configurator\ParametersConfigurator $parameters */
$parameters->set('env', 'dev');
if(file_exists(".env.dev")){
    $env = file(".env.dev");
} else if (file_exists(".env")) {
    $env =file(".env");
} else {
    var_dump("there are no parameters to start the server ");
} 
foreach($env as $param){
    if(str_starts_with($param, '#')){
        continue;
    }
    [$key, $value] = explode("=", $param);
    $value = trim(str_replace("\"", "", $value));
    var_dump($key,$value);
    $parameters->set($key,$value);
   
}
