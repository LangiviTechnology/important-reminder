<?php
namespace Langivi\ImportantReminder\Controllers;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AbstractController
{
    private readonly ContainerBuilder $containerBuilder;

    public function setContainer(ContainerBuilder $container): self
    {
        $this->containerBuilder = $container;
        return $this;
    }
}