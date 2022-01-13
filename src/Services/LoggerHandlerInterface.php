<?php 

namespace Langivi\ImportantReminder\Services;


interface LoggerHandlerInterface
{
	public const DEFAULT_FORMAT = '%timestamp% [%level%]: %message%';
    public function handle(array $vars):void;
}
