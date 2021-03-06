<?php 

namespace Langivi\ImportantReminder\Services;

use Langivi\ImportantReminder\Interfaces\LoggerHandlerInterface;

class LoggerService
{
    public const LEVELS = [
		'DEBUG' => ['ERROR', 'WARNING', 'INFO', 'DEBUG'],
		'PROD' => ['ERROR', 'WARNING'],
		'DEFAULT' => ['ERROR'],
	];
    private readonly LoggerHandlerInterface $handler;
    private string $mode = 'DEFAULT';

    public function __construct(LoggerHandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return LoggerHandlerInterface
     */
    public function getHandler(): LoggerHandlerInterface
    {
        return $this->handler;
    }

    public function setMode($mode) 
    {
        $this->mode = $mode;
    }


    public function log(string $level, string $message, array $context = array())
    {
        if (in_array($level, self::LEVELS[$this->mode])) {
            $this->handler->handle([
                'timestamp' => (new \DateTimeImmutable())->format('c'),
                'level' => strtoupper($level),
                'message' => $message,
                'context' => self::contextToString($context),
            ]);
        }
    }

	protected static function contextToString(array $context): string
    {
        $result = '';
        foreach ($context as $key => $value) {
            $result .= $key . ' = ' . $value . '; ';
        }
        return $result;
    }

    public function error($message, array $context = array()) {
        $this->log('ERROR', $message, $context);
    }
    
    public function warning($message, array $context = array()) {
        $this->log('WARNING', $message, $context);
    }

    public function info($message, array $context = array()) {
        $this->log('INFO', $message, $context);
    }

    public function debug($message, array $context = array()) {
        $this->log('DEBUG', $message, $context);
    }
}
