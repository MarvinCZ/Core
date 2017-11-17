<?php

namespace Core;

class View
{
	private $basePath = "";
	private $twig;

	/**
	 * View constructor.
	 */
	public function __construct()
	{
		$loader = new \Twig_Loader_Filesystem($this->basePath);
		$this->twig = new \Twig_Environment($loader);
	}


	public function renderToString($template, $args = []) {
		return $this->twig->render($template, $args);
	}
}