<?php

declare(strict_types=1);

namespace App\Controller;

use Core\Controller\BaseController;

class HomePageController extends BaseController
{
	public function index()
	{
		$this->renderView('homepage/index.twig');
	}
}