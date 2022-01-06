<?php

class Route
{
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var Collable 
	 */
	private $controller;

	/**
	 * @var array<string>
	 */
	private $methods = [];

	/**
	 * @var array<string>
	 */
	private $vars = [];


	public function __construct(string $name, string $path, $controller, array $methods = ['GET'], array $vars = [])
	{
		// TODO is need chech exist at least one method?
		$this->name = $name;
		$this->path = $path;
		$this->controller = $controller;
		$this->methods = $methods;
		$this->vars = $vars;
	}

	public function match(string $uri, string $method): bool
	{
		$requestPath = strtolower(trim($uri, '{\}'));
		$routePath = strtolower(trim($this->path, '{\}'));
		if (strcmp($requestPath, $routePath) !== 0) {
			return false;
		}

		if (!in_array($method, $this->getMethods())) {
			return false;
		}

		return true;
	}

	public function getName(): string 
	{
		return $this->name;
	}

	public function getPath(): string 
	{
		return $this->path;
	}

	public function getController() 
	{
		return $this->controller;
	}

	public function getMethods(): array 
	{
		return $this->methods;
	}

	public function getVars(): array
	{
		return $this->vars;
	}

	public function hasVars(): bool
	{
		return boolval($this->vars);
	}
}