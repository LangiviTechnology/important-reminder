<?php

/** @var Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator $services */
$services->load('Langivi\ImportantReminder\Services\\', './Services/*')->public()->autowire()->tag('service');
$services->load('Langivi\ImportantReminder\Handlers\\', './Handlers/*')->public()->autowire()->tag('handlers');
$services->load('Langivi\ImportantReminder\Interfaces\\', './Interfaces/*')->public()->autowire()->tag('handlers');
$services->load('Langivi\ImportantReminder\MiddleWares\\', './MiddleWares/*')->public()->autowire()->tag('middlewares');
