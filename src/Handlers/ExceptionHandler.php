<?php 

namespace Langivi\ImportantReminder\Handlers;

use Langivi\ImportantReminder\Services\LoggerService;
use Langivi\ImportantReminder\Response\AbstractResponse;
use Langivi\ImportantReminder\Response\JsonResponse;

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
			AbstractResponse $response,
			string $messsge, 
			int $status, 
			array $payload
		) {
		
		$status = $status ? $status : 500;
		$response->setStatusCode($status);
		$this->logger->error($messsge, ['status' => $status, ...$payload]);

		if (get_class($response) === JsonResponse::class) {
            $response->send(json_encode((object)['error' => $messsge]));
            return;   
		}
		
		$response->send(
			$this->twig->render('error.twig', ['title' => 'Error', 'message' => $messsge, 'status' => $status])
		);
	}
 }
 


