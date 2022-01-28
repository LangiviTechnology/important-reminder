<?php

namespace Langivi\ImportantReminder\MiddleWares;


function getCookie(string|null $rawCookie = '', string $name): string|bool
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

function authMiddleware(\HttpRequest $request, \HttpResponse $response) {
    $accessToken = getCookie($request->headers['Cookie'], 'accessToken');
    if (!$accessToken){
        $response->setHeader("Content-Type", "application/json; charset=utf-8");
        $response->setStatusCode(401);
        $response->send(json_encode(
            (object)['error' => "Unauthorized"]
        ));
        return; 
    }
}