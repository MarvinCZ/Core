<?php

namespace Core\Http;

use Core\Exceptions\UndefinedParameter;
use Core\RestMethods;

class Request
{
	private $url;

	public function __construct()
	{
		$this->url = rtrim($_SERVER['REQUEST_URI'], '/');
	}

	public function getMethod()
	{
		if ($_SERVER['REQUEST_METHOD'] == RestMethods::GET) {
			return RestMethods::GET;
		}

		if (isset($_POST['method']) && $_POST['method'] == RestMethods::DELETE) {
			return RestMethods::DELETE;
		}

		return RestMethods::POST;
	}

	public function getParameter($key)
	{
		if (isset($_GET[$key]) && $this->getMethod() == RestMethods::GET) {
			return $_GET[$key];
		}

		if (isset($_POST[$key])) {
			return $_POST[$key];
		}

		if (isset($_GET[$key])) {
			return $_GET[$key];
		}

		throw new UndefinedParameter($key);
	}

	public function validate()
	{
		return TRUE;
	}

	public function getUrl()
	{
		return $this->url;
	}
}