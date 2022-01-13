<?php
namespace Langivi\ImportantReminder\Controllers;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AbstractController
{
    private readonly ContainerBuilder $containerBuilder;
    public function __construct()
    {
    }

    public function setContainer(ContainerBuilder $container): self
    {
        $this->container = $container;
        return $this;
    }
}