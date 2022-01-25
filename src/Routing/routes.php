<?php

use Langivi\ImportantReminder\Routing\HttpMethods;
use Langivi\ImportantReminder\Routing\Route;

return [
    Route::create('/', 'IndexController::index', 'index', [HttpMethods::GET, HttpMethods::POST],),
    Route::create('/test', function (HttpRequest $request, HttpResponse $response) {
        $response->send("test");
    }, 'index', [HttpMethods::GET],),
    Route::create('/auth/login', 'AuthController::login', 'login', [HttpMethods::GET, HttpMethods::POST]),
    Route::create('/auth/logout', 'AuthController::logout', 'logout', [HttpMethods::GET]),
    Route::create('/auth/registration', 'AuthController::registration', 'registration', [HttpMethods::GET, HttpMethods::POST],),
    Route::create('/events/all', 'EventController::all', 'all', [HttpMethods::GET]),
    Route::create('/events/add', 'EventController::add', 'add', [HttpMethods::POST]),
    Route::create('/events/update', 'EventController::update', 'update', [HttpMethods::PUT]),
    Route::create('/events/delete', 'EventController::delete', 'delete', [HttpMethods::DELETE]),
    Route::create('/events', 'EventController::findOne', 'get', [HttpMethods::GET], ['id']),
// TODO might add route nerby/current events?
];