<?php 

namespace Langivi\ImportantReminder\Services;

use Langivi\ImportantReminder\Entity\User;
use Langivi\ImportantReminder\Services\TokenService; 

class UserService
{
    private const permissonsFile = 'allowed_users.ini';
    private static $allowedUsers = [];

	public function __construct(
        private TokenService $tokenService,
	) {

		// $allowedUsersFile = $this->kernel->getDataDir() . '/'
		// 	. self::permissonsFile;
		// if ($this->filesystem->exists($allowedUsersFile)) {
			
		// }

	}

    public function register(User $userDto): User
    {

    }

	public function login(string $userId)
    {
		$tokens = $this->tokenService->generateTokens($userId);
        $this->tokenService->saveToken($userId, $tokens->refreshToken);
		return $tokens;
    }

	public function findOne(string $email): User | bool
    {
		$user = new User ('000spoksdpoasdk', 'guest', 'test@mail.com', 'sdfiohHUIIdsSDd');
        // Get user by mail from repo
		return $user;
    }
	public function isAllowed(string $email): bool
    {
        return true;
    }
	public function comparePassword(string $password): bool
    {
        return false;
    }

}
