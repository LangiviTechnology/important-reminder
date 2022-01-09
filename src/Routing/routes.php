<?php

use Langivi\ImportantReminder\Routing\Route;

return [

    Route::create('/', 'IndexController::index', 'index', ['GET', 'POST'],),

];