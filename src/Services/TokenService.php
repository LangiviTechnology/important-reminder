<?php 

namespace Langivi\ImportantReminder\Services;


class TokenService
{

	public function __construct(
	) {
	}

    public function generateTokens($payload)
    {
		$accessToken = '';
		$refreshToken = '';
		return (object) [
			'accessToken' => $accessToken,
			'refreshToken' => $refreshToken,
		];
    }

	public function validationAccessToken(string $token) :bool
    {
		return true;
    }

	public function validationRefreshToken(string $token) :bool
    {
		return true;
    }

	public function findToken(string $refreshToken): string | bool
    {
		return false;
    }
	public function removeToken(string $email): string
    {
        return '';
    }
	public function saveToken(string $userId,string $refreshToken): string
    {
        return '';
    }

}
