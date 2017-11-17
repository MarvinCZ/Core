<?php

namespace Tests\RouterTests;

use Core\Http\Request;
use Core\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
	public function testRouteMatching()
	{
		$route = new Route('GET', '/news', NULL, NULL);
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['REQUEST_URI'] = '/news/';
		$request = new Request();
		$this->assertTrue($route->matches($request));
	}

	public function testRouteParameters()
	{
		$route = new Route('GET', '/news/{id:int}/{name:string}', NULL, NULL);
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['REQUEST_URI'] = '/news/56/test';
		$request = new Request();
		$route->matches($request);
		$params = $route->getParams();
		$this->assertSame('56', $params['id']);
		$this->assertSame('test', $params['name']);
	}
}
