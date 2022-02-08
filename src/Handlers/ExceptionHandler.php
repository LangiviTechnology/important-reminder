<?php

namespace Langivi\ImportantReminder\Handlers;

use Langivi\ImportantReminder\Services\LoggerService;
use Langivi\ImportantReminder\Response\AbstractResponse;
use Langivi\ImportantReminder\Response\JsonResponse;

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

		$statusCode = $status !== 0 ?? 500;
		$response->setStatusCode($statusCode);
		$this->logger->error($message, ['status' => $statusCode, ...$payload]);
		$response->error(['error' => $message]);

	}
 }
 


