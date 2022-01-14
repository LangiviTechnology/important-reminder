<?php 

namespace Langivi\ImportantReminder\Interfaces;

interface LoggerHandlerInterface
{
	public const DEFAULT_FORMAT = '%timestamp% [%level%]: %message%';
    public function handle(array $vars):void;
}
