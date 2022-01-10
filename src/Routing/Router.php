<?php
namespace Langivi\ImportantReminder\Routing;


use ArrayObject;

class Router
{
	/**
	 * @var ArrayObject<Route>
	 */
	private ArrayObject $routes;
	public function __construct(array $routes = [])
	{
		$this->routes = new ArrayObject();

		foreach ($routes as $route) {
			$this->add($route);
		}
	}

	public function add(Route $route): self
	{
		$this->routes->offsetSet($route->getName(), $route);
		return $this;
	}


	public function matchFromPath(string $uri, HttpMethods $method): Route | null
	{
		foreach ($this->routes as $route) {
			if ($route->match($uri, $method) === false) {
				continue;
			}
			return $route;
		}
		return null;
		// TODO: Add Main error handler
		// throw new \Exception('No route found for method ' . $method->value, 404);
	}
}