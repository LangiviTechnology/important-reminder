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

    public function middleware(\HttpRequest $request, AbstractResponse $response): bool
    {
        $cookie = $request->headers['Cookie'] ?? '';
        $accessToken = getCookie($cookie, 'accessToken');
        if (!$accessToken || !$this->tokenService->validationAccessToken($accessToken)) 
        {
            return false;
        }
        return true;
    }
}