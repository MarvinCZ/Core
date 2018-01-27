<?php

declare(strict_types=1);

namespace Core;

class Session
{
	public function __construct()
	{
		session_start();
	}

	public function get($key)
	{
		return $_SESSION[$key] ?? NULL;
	}

	public function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	public function unset($key)
	{
		unset($_SESSION[$key]);
	}
}