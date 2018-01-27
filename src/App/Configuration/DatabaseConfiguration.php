<?php

declare(strict_types=1);

namespace App\Configuration;

use Core\Database\IDatabaseConfiguration;

class DatabaseConfiguration implements IDatabaseConfiguration
{

	public function getHost(): string
	{
		return 'localhost';
	}

	public function getDatabaseName(): string
	{
		return 'web_konference';
	}

	public function getUsername(): string
	{
		return 'root';
	}

	public function getPassword(): string
	{
		return 'mojeheslo';
	}
}