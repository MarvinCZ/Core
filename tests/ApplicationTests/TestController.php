<?php


namespace Tests\ApplicationTests;


use Core\Controller\BaseController;
use Core\Router;

class TestController extends BaseController
{
	public function __construct(Router $dependency)
	{
	}

	public function index()
	{
		throw new IndexCalledException();
	}

	public function show($id)
	{
		throw new ShowCalledException();
	}
}