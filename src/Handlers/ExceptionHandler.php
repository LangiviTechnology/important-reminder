<?php 

namespace Langivi\ImportantReminder\Handlers;

use Langivi\ImportantReminder\Services\LoggerService;

class ExceptionHandler {
	private $twig;
	public function __construct(
		private LoggerService $logger,
	) 
	{
	}

	public function setTwig($twig){
		$this->twig = $twig;
	}

	public function sendError(
			\HttpResponse $response, 
			string $messsge, 
			int $status = 500, 
			array $payload = []
		) {
		$this->logger->error($messsge, ['status' => $status, ...$payload]);
		$response->setStatusCode($status);

		if ($response->_type && $response->_type === 'json') {
			$response->setHeader("Content-Type", "application/json");
            $response->send(json_encode((object)['error' => $messsge]));
            return;   
		}

		$response->setHeader("Content-Type", "text/html; charset=utf-8");
		$response->send(
			$this->twig->render('error.twig', ['title' => 'Error', 'message' => $messsge, 'status' => $status])
		);
	}
 }
 


