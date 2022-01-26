<?php
namespace Langivi\ImportantReminder\Controllers;

use Langivi\ImportantReminder\Entity\User;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Langivi\ImportantReminder\Loader;
use Langivi\ImportantReminder\Services\UserService;
use Langivi\ImportantReminder\Services\LoggerService;

class AuthController extends AbstractController
{
    public function __construct(
        private UserService $userService,
        private LoggerService $logger,
    )
    {
    }

    public function registration(\HttpRequest $request, \HttpResponse $response)
    {
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
        echo '---------------------------------';
        // var_dump($candidate->getData());
        var_dump($candidate->validate());

        $response->setHeader("Content-Type", "application/json");

        if (!$candidate->validate()) {
            $response->setStatusCode(400);
            $response->send(json_encode(
                (object)['error' => "Incorrect data"]
            ));
            return;    
        }

        if (!$this->userService->isAllowed($candidate->getEmail())) {
            $response->setStatusCode(403);
            $response->send(json_encode(
                (object)['error' => "Access not allowed"]
            ));
            return;
        }

        if ($this->userService->findOne($candidate->getEmail())) {
            $response->setStatusCode(409);
            $response->send(json_encode(
                (object)['error' => "User already exist"]
            ));
            return;
        }
        $userData = $this->userService->register($candidate);
        var_dump('userdata', $userData);
        $this->logger->info('Register user', ['email' => $candidate->getEmail()]);
        // set cokie

        $response->send(json_encode($userData));
    }

	public function login(\HttpRequest $request, \HttpResponse $response)
    {
        $twig = $this->containerBuilder->get('twig');
        if ($request->method === 'GET') {
            $response->setHeader("Content-Type", "text/html; charset=utf-8");
            $response->send($twig->render('login.twig', ['title' => 'Login']));
            return;
        }

        // var_dump($request->body);
        $email = $request->body['email'];
        $password = $request->body['password'];

        $response->setHeader("Content-Type", "application/json");
        
        if (!$email && !$password) {
            $response->setStatusCode(400);
            $response->send(json_encode(
                (object)['error' => "Incorrect data"]
            ));
            return;
        }

        if (!$this->userService->isAllowed($email)) {
            $response->setStatusCode(403);
            $response->send(json_encode(
                (object)['error' => "Access not allowed"]
            ));
            return;
        }

        $user = $this->userService->findOne($email);
        if (!$user) {
            $response->setStatusCode(404);
            $response->send(json_encode(
                (object)['error' => "User not found"]
            ));
            return;
        }

        if (!$this->userService->comparePassword($password, $user->getPassword())) {
            $response->setStatusCode(404);
            $response->send(json_encode(
                (object)['error' => "Incorrect login, password"]
            ));
            return;
        }

        $userData  = $this->userService->login($user);
        setcookie(
            'refreshToken', 
            $userData->tokens->refreshToken, 
            time()+3600);
        $response->send(json_encode($userData));
    }

	public function logout(\HttpRequest $request, \HttpResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("logout");
    }

    public function refresh(\HttpRequest $request, \HttpResponse $response)
    {
        var_dump($_COOKIE);
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("logout");
    }
}