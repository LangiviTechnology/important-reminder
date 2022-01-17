<?php 

namespace Langivi\ImportantReminder\Utils;

use Langivi\ImportantReminder\Interfaces\LoggerHandlerInterface;
use UnexpectedValueException;

class LoggerHandler implements LoggerHandlerInterface
{
    private string $filename ='';

	public function setFilename($filename)
	{
        $dir =  dirname($filename);
		if (!file_exists($dir)) {
			$status = mkdir($dir, 0777, true);
			if ($status === false && !is_dir($dir)) {
				throw new UnexpectedValueException(sprintf('There is no existing directory at "%s"', $dir));
			}
		}
		$this->filename = $filename;
	}

    public function handle(array $vars) :void
    {
		$output = self::DEFAULT_FORMAT;
		foreach ($vars as $var => $value) {
			$output = str_replace('%' . $var . '%', $value, $output);
		}
		echo $output;
		file_put_contents_async($this->filename, $output . PHP_EOL, function(){
			echo 'file writed \n  ';
		});
    }
}
