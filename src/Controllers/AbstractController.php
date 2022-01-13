<?php
namespace Langivi\ImportantReminder\Controllers;
use Symfony\Component\DependencyInjection\ContainerBuilder;
class AbstractController{
    public function setContainer(ContainerBuilder $container): self
    {
        $this->container = $container;
        return $this;
    }
}