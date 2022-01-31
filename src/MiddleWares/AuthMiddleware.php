<?php

namespace Langivi\ImportantReminder\MiddleWares;

use Langivi\ImportantReminder\Services\TokenService; 

class AuthMiddleware
{
	public function __construct(
        private TokenService $tokenService,
	) {

	}

    public function getCookie(string $rawCookie, string $name): string|bool
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


    public function middleware(\HttpRequest $request, \HttpResponse $response): bool
    {
        $cookie = $request->headers['Cookie'] ?? '';
        $accessToken = $this->getCookie($cookie, 'accessToken');
        if (!$accessToken || !$this->tokenService->validationAccessToken($accessToken)) 
        {
            // TODO separate api and html reaquests
            $response->setHeader("Content-Type", "application/json; charset=utf-8");
            $response->setStatusCode(401);
            $response->send(json_encode(
                (object)['error' => "Unauthorized"]
            ));
            return false;
        }
        return true;
    }
}