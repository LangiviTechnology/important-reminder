<?php
namespace Langivi\ImportantReminder\Controllers;

use Langivi\ImportantReminder\Entity\User;
use Langivi\ImportantReminder\Services\UserService;
use Langivi\ImportantReminder\Services\LoggerService;
use Langivi\ImportantReminder\Handlers\ExceptionHandler;
use Langivi\ImportantReminder\Response\AbstractResponse;
use Exception;

use function Langivi\ImportantReminder\Utils\getCookie;

class AuthController extends AbstractController
{
    public function __construct(
        private UserService $userService,
        private LoggerService $logger,
        private ExceptionHandler $exceptionHandler,
    )
    {
    }

    public function registration(\HttpRequest $request, AbstractResponse $response)
    {
        $twig = $this->containerBuilder->get('twig');
        if ($request->method === 'GET') {
            $response->setTemplate('registration.twig');
            $response->send(['title' => 'Registration']);
            return;
        }

        $candidate = new User(
            $request->body['login'],
            $request->body['email'],
            $request->body['password']
        );
        // var_dump($candidate->getData());
        // var_dump($candidate->validate());
        
        // TODO: rewrite to appropriate exception classes
        if (!$candidate->validate()) {
            throw new Exception('Incorrect data', 400);
        }
        if (!$this->userService->isAllowed($candidate->getEmail())) {
            throw new Exception('Access not allowed', 403);
        }
        if ($this->userService->findOne($candidate->getEmail())) {
            throw new Exception('Incorrect login, password', 409);
        }

        $userData = $this->userService->register($candidate);
        $this->logger->info('Register user', ['email' => $candidate->getEmail()]);
        
        // TODO resolve problem Max age
        $maxAge = 3600 * 24 * 15;
        $response->setHeader("Set-Cookie",'refreshToken=' . $userData->tokens->refreshToken . '; Path=/; Max-Age=' . $maxAge . '; HttpOnly');
        $response->setHeader("Set-Cookie",'accessToken=' . $userData->tokens->accessToken . '; Path=/; Max-Age=900;');
        $response->send($userData);
    }

	public function login(\HttpRequest $request, AbstractResponse $response)
    {
        $twig = $this->containerBuilder->get('twig');
        if ($request->method === 'GET') {
            $response->setTemplate('login.twig');
            $response->send(['title' => 'Login']);
            return;
        }

        // var_dump($request->body);
        $email = $request->body['email'];
        $password = $request->body['password'];

        // TODO: rewrite to appropriate exception classes
        if (!$email && !$password) {
            throw new Exception('Incorrect data', 400);
        }

        if (!$this->userService->isAllowed($email)) {
            throw new Exception('Access not allowed', 403);
        }
        
        $user = $this->userService->findOne($email);
        if (!$user) {
            // TODO: rewrite to appropriate exception classes     
            throw new Exception('Incorrect login, password', 404);
        }
        if (!$this->userService->comparePassword($password, $user->getPassword())) {
            throw new Exception('Incorrect login, password', 404);
        }
        
        $userData = $this->userService->login($user);
        $this->logger->info('Login user', ['email' => $userData->user->email]);
        
        $maxAge = 3600 * 24 * 15;
        $response->setHeader("Set-Cookie",'refreshToken=' . $userData->tokens->refreshToken . '; Path=/; Max-Age=' . $maxAge . '; Secure; HttpOnly');
        $response->setHeader("Set-Cookie",'accessToken=' . $userData->tokens->accessToken . '; Path=/; Max-Age=20;');
        
        $response->send($userData);
    }

	public function logout(\HttpRequest $request, AbstractResponse $response)
    {
        $cookie = $request->headers['Cookie'] ?? '';
        $refreshToken = getCookie($cookie, 'refreshToken');
        
        if (!$refreshToken){
            throw new Exception('Unauthorized', 401);
        }

        // TODO split types user DTO (id, login) and native user
        // TODO add return userDto 
        $this->userService->logout($refreshToken);
        // $this->logger->info('Logout user', ['email' => $userData->user->getEmail()]);
        
        // TODO rework delete cookie
        $response->setHeader("Set-Cookie",'refreshToken=0; Path=/; Max-Age=0;');
        $response->setHeader("Set-Cookie",'accessToken=0; Path=/; Max-Age=0;');
        
        $response->send(['logout'=> true]);
    }

    public function refresh(\HttpRequest $request, AbstractResponse $response)
    { 
        $cookie = $request->headers['Cookie'] ?? '';
        $refreshToken = getCookie($cookie, 'refreshToken');
        
        // TODO: rewrite to appropriate exception classes     
        if (!$refreshToken){
            throw new Exception('Unauthorized', 401);
        }
        $userData = $this->userService->refresh($refreshToken, '');
        if (!$userData){
            throw new Exception('Unauthorized', 401);
        }

        $maxAge = 3600 * 24 * 15;
        $response->setHeader("Set-Cookie",'refreshToken=' . $userData->tokens->refreshToken . '; Path=/; Max-Age=' . $maxAge . '; HttpOnly');
        $response->setHeader("Set-Cookie",'accessToken=' . $userData->tokens->accessToken . '; Path=/; Max-Age=900;');
        $response->send($userData);
    }
}