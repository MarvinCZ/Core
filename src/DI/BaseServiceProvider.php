<?php

namespace Core\DI;

use Core\Http\Request;
use Core\Router;

class BaseServiceProvider implements IServiceProvider
{

	public function getServices()
	{
		return [
			Request::class => Request::class,
			Router::class => Router::class,
		];
	}
}