<?php 

namespace Langivi\ImportantReminder\Utils;


function getCookie(string $rawCookie, string $name): string|bool
{
	//TODO might need better parser cookie
	parse_str(strtr($rawCookie, array('&' => '%26', '+' => '%2B', ';' => '&')), $cookies);
	if (!array_key_exists($name, $cookies)){
		return false; 
	}
	return $cookies[$name];
}