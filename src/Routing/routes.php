<?php

use Langivi\ImportantReminder\Routing\HttpMethods;
use Langivi\ImportantReminder\Routing\Route;

return [

    Route::create('/', 'IndexController::index', 'index', [HttpMethods::GET, HttpMethods::POST],),

];