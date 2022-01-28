<?php
namespace Langivi\ImportantReminder\Controllers;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AbstractController
{
    protected readonly ContainerBuilder $containerBuilder;

    public function setContainer(ContainerBuilder $container): self
    {
        $this->containerBuilder = $container;
        return $this;
    }

    public function getCookie(string|null $rawCookie = '', string $name): string|bool
    {
        //TODO might need better parser cookie
        parse_str(strtr($rawCookie, array('&' => '%26', '+' => '%2B', ';' => '&')), $cookies);
        var_dump('========', $rawCookie, $name);
        var_dump('========', $cookies);
        if (!array_key_exists($name, $cookies)){
            return false; 
        }
        return $cookies[$name];
    }
}