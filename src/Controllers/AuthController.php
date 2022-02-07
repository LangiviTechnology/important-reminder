<?php
namespace Langivi\ImportantReminder\Controllers;

use Langivi\ImportantReminder\Entity\User;
use Langivi\ImportantReminder\Services\UserService;
use Langivi\ImportantReminder\Services\LoggerService;
use Langivi\ImportantReminder\Handlers\ExceptionHandler;
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
        try {
            $twig = $this->containerBuilder->get('twig');
            if ($request->method === 'GET') {
                $response->setHeader("Content-Type", "text/html; charset=utf-8");
                $response->send($twig->render('registration.twig', ['title' => 'Registration']));
                return;
            }

            $candidate = new User(
                $request->body['login'],
                $request->body['email'],
                $request->body['password']
            );
            // var_dump($candidate->getData());
            // var_dump($candidate->validate());

            $response->setHeader("Content-Type", "application/json");
            
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
            $response->send(json_encode($userData));

        } catch (\Throwable $th) {
            $this->exceptionHandler->sendError($response, $th->getMessage(), $th->getCode(), ['path'=> $request->uri]);
        }
    }

	public function login(\HttpRequest $request, AbstractResponse $response)
    {
        try {
            $twig = $this->containerBuilder->get('twig');
            if ($request->method === 'GET') {
                $response->setHeader("Content-Type", "text/html; charset=utf-8");
                $response->send($twig->render('login.twig', ['title' => 'Login']));
                return;
            }

            // var_dump($request->body);
            $email = $request->body['email'];
            $password = $request->body['password'];

            if (!$email && !$password) {
                throw new Exception('Incorrect data', 400);
            }

            if (!$this->userService->isAllowed($email)) {
                throw new Exception('Access not allowed', 403);
            }
            
            $user = $this->userService->findOne($email);
            if (!$user) {
                throw new Exception('Incorrect login, password', 404);
            }
            
            if (!$this->userService->comparePassword($password, $user->getPassword())) {
                throw new Exception('Incorrect login, password', 404);
            }
            
            $response->setHeader("Content-Type", "application/json");
            $userData = $this->userService->login($user);
            $this->logger->info('Login user', ['email' => $userData->user->email]);
            
            $maxAge = 3600 * 24 * 15;
            $response->setHeader("Set-Cookie",'refreshToken=' . $userData->tokens->refreshToken . '; Path=/; Max-Age=' . $maxAge . '; Secure; HttpOnly');
            $response->setHeader("Set-Cookie",'accessToken=' . $userData->tokens->accessToken . '; Path=/; Max-Age=20;');
            
            $response->send(json_encode($userData));
        } catch (\Throwable $th) {
            $this->exceptionHandler->sendError($response, $th->getMessage(), $th->getCode(), ['path'=> $request->uri]);
        }
    }

	public function logout(\HttpRequest $request, AbstractResponse $response)
    {
        try {
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
            
            $response->setHeader("Content-Type", "application/json; charset=utf-8");
            $response->send(json_encode(['logout'=> true]));
        
        } catch (\Throwable $th) {
            $this->exceptionHandler->sendError($response, $th->getMessage(), $th->getCode(), ['path'=> $request->uri]);
        }
    }

    public function refresh(\HttpRequest $request, AbstractResponse $response)
    { 
        $cookie = $request->headers['Cookie'] ?? '';
        $refreshToken = getCookie($cookie, 'refreshToken');
        $response->setHeader("Content-Type", "application/json");
        if (!$refreshToken){
            $response->setStatusCode(401);
            $response->send(json_encode(
                (object)['error' => "Unauthorized"]
            ));
            return; 
        }
        $userData = $this->userService->refresh($refreshToken, '');
        if (!$userData){
            $response->setStatusCode(401);
            $response->send(json_encode(
                (object)['error' => "Unauthorized"]
            ));
            return; 
        }

        $maxAge = 3600 * 24 * 15;
        $response->setHeader("Set-Cookie",'refreshToken=' . $userData->tokens->refreshToken . '; Path=/; Max-Age=' . $maxAge . '; HttpOnly');
        $response->setHeader("Set-Cookie",'accessToken=' . $userData->tokens->accessToken . '; Path=/; Max-Age=900;');
        $response->send(json_encode($userData));
    }
}