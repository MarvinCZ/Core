<?php

namespace Core;

use Core\DI\Container;

class Application
{

	private $container;

	public function __construct()
	{
		$this->container = new Container();
	}

	public function getController() {

	}

}