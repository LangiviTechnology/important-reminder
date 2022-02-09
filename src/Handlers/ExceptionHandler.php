<?php

namespace Langivi\ImportantReminder\Handlers;

use Langivi\ImportantReminder\Services\LoggerService;
use Langivi\ImportantReminder\Response\AbstractResponse;

class ExceptionHandler {
	public function __construct(
		private LoggerService $logger,
	)
	{
	}

	public function sendError(
			AbstractResponse $response,
			string $message,
			int $status,
			array $payload
		) {
		$statusCode = empty($status) ? 500 : $status;
		$this->logger->error($message, ['status' => $statusCode, ...$payload]);
		
		$response->setStatusCode($statusCode);
		$response->error(['error' => $message, 'status' => $statusCode]);
	}
 }
 


