<?php

namespace Langivi\ImportantReminder\MiddleWares;

use Langivi\ImportantReminder\Services\TokenService; 
use function Langivi\ImportantReminder\Utils\getCookie;
require_once 'src/Utils/getCookie.php';

class AuthMiddleware
{
	public function __construct(
        private TokenService $tokenService,
	) {

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