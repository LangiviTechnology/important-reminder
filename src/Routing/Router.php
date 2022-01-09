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


	public function matchFromPath(string $uri, string $method): Route
	{
		foreach ($this->routes as $route) {
			if ($route->match($uri, $method) === false) {
				continue;
			}
			return $route;
		}
		
		throw new \Exception('No route found for method ' . $method, 404);
	}
}