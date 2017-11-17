<?php

namespace Core;

class View
{
	private $basePath = "";

	public function renderToString($template, $args = []) {
		static $twig = null;
		if ($twig === null) {
			$loader = new \Twig_Loader_Filesystem($this->basePath);
			$twig = new \Twig_Environment($loader);
		}
		return $twig->render($template, $args);
	}
}