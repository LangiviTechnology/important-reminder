<?php 

namespace Langivi\ImportantReminder\Services;

class LoggerService
{
    public const LEVELS = [
		'DEBUG' => ['ERROR', 'WARNING', 'INFO', 'DEBUG'],
		'PROD' => ['ERROR', 'WARNING'],
		'DEFAULT' => ['ERROR'],
	];
    private $handler;
    private $mode = 'DEFAULT';

	public function __construct()
	{
	}

    public function setHandler(LoggerHandlerInterface $handler) 
    {
        $this->handler = $handler;
    }

    public function setMode($mode) 
    {
        $this->mode = $mode;
    }


    public function log($level, $message, array $context = array())
    {
        if (in_array($level, self::LEVELS[$this->mode])) {
            $this->handler->handle([
                'timestamp' => (new \DateTimeImmutable())->format('c'),
                'level' => strtoupper($level),
                'message' => $message,
                'context' => self::contextToString($message, $context),
            ]);
        }
    }

	protected static function contextToString(array $context): string
    {
        $result = '';
        foreach ($context as $key => $value) {
            $result = $result . '[' . $key . ']=' . $value . '; ';
        }
        return $result;
    }

    public function error($message, array $context) {
        $this->log('EROOR', $message, $context);
    }
    
    public function warning($message, array $context) {
        $this->log('WARNING', $message, $context);
    }

    public function info($message, array $context) {
        $this->log('INFO', $message, $context);
    }

    public function debug($message, array $context) {
        $this->log('DEBUG', $message, $context);
    }
}
