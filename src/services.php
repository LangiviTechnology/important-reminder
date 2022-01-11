<?php

 return $services->load('Langivi\ImportantReminder\Services\\', './Services/*')->public()->autowire()->tag('service');
