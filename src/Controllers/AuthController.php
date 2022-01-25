<?php
namespace Langivi\ImportantReminder\Controllers;
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

    public function index(\HttpRequest $request, \HttpResponse $response)
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
        $this->logger->info('Register user');

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

        $tokens = $this->userService->login($user->getId());
        $response->send(json_encode(
            (object)['user' => $user->getData(), 'tokens' => $tokens]
        ));
    }

	public function logout(\HttpRequest $request, \HttpResponse $response)
    {
        $response->setHeader("Content-Type", "text/plain; charset=utf-8");
        $response->send("logout");
    }
}