<?php

/** @var Symfony\Component\DependencyInjection\Loader\Configurator\ServicesConfigurator $services */
return $services->load('Langivi\ImportantReminder\Services\\', './Services/*')->public()->autowire()->tag('service');
