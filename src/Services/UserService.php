<?php 

namespace Langivi\ImportantReminder\Services;

use Langivi\ImportantReminder\Entity\User;
use Langivi\ImportantReminder\Services\TokenService; 
use Langivi\ImportantReminder\Services\LoggerService;


class UserService
{
    private const permissonsFile = 'allowed_users.ini';
    private $allowedUsers = array();

	public function __construct(
        private TokenService $tokenService,
        private LoggerService $logger,
	) {
        $allowedUsersFile = dirname(__DIR__, 2) . '/var/' . self::permissonsFile;
		if (!file_exists($allowedUsersFile)) {
			$this->logger->error('allowed_users.ini not found');
        } else {
            $this->allowedUsers = parse_ini_file($allowedUsersFile);
        }
        if (count($this->allowedUsers) === 0) {
            $this->logger->error('allowed users is empty');
        }
	}

    public function register(User $candidat): object
    {
        
        $hashPassword = password_hash($candidat->getPassword(), PASSWORD_BCRYPT);
        // TODO Rework to create user in DB
        $registeredUser = new User(
            $candidat->getLogin(),
            $candidat->getEmail(),
            $hashPassword 
        );
        // var_dump($registeredUser);
        $tokens = $this->tokenService->generateTokens(
            (object)['id' => $registeredUser->getId(), 
            'login' => $registeredUser->getLogin()]
        );
        $this->tokenService->saveToken($registeredUser->getId(), $tokens->refreshToken);

        return (object)['user' => $registeredUser->getData(), 'tokens' => $tokens];
    }

	public function login(User $userDto)
    {
		$tokens = $this->tokenService->generateTokens(
            (object)['id' => $userDto->getId(), 
            'login' => $userDto->getLogin()]
        );
        $this->tokenService->saveToken($userDto->getId(), $tokens->refreshToken);
		return (object)['user' => $userDto->getData(), 'tokens' => $tokens];
    }

    public function logout(string $refreshToken): void
    {
        $this->tokenService->removeToken($refreshToken);      
    }

    public function refresh(string $refreshToken): object
    {
		$userFromToken = $this->tokenService->validationRefreshToken($refreshToken);
        $tokenFromDb = $this->tokenService->findOne($refreshToken);  
        if (!$userFromToken || !$tokenFromDb) {
            return null;
        }
        
        $userDto = $this->findById($userFromToken->id);
        $tokens = $this->tokenService->generateTokens(
            (object)['id' => $userDto->getId(), 
            'login' => $userDto->getLogin()]
        );
        $this->tokenService->saveToken($userDto->getId(), $tokens->refreshToken);

		return (object)['user' => $userDto->getData(), 'tokens' => $tokens];
    }

	public function findById(string $id): User | null
    {
		$user = new User ('Admin', 'admin@langivi.com', '$2y$10$9sm3gGJqQSvp7Gt0J6s2iOAYmeERwpOXIEZPPLZUE.XPHd3FAK3G.');
        // TODO Get user by mail from repo
		if ($id == '$id') return $user;
        return null;
    }

    public function findOne(string $email): User | null
    {
		$user = new User ('Admin', 'admin@langivi.com', '$2y$10$9sm3gGJqQSvp7Gt0J6s2iOAYmeERwpOXIEZPPLZUE.XPHd3FAK3G.');
        // TODO Get user by mail from repo
		if ($email == 'admin@langivi.com') return $user;
        return null;
    }

	public function isAllowed(string $email): bool
    {
        if (array_key_exists($email, $this->allowedUsers)) {
            return true;
        }
        return false;
    }
    
	public function comparePassword(string $password, string $userPassword): bool
    {
        return password_verify($password, $userPassword);
    }

}
