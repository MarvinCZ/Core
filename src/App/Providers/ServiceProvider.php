<?php

declare(strict_types=1);

namespace App\Providers;

use App\Configuration\DatabaseConfiguration;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Core\Database\IDatabaseConfiguration;
use Core\DI\IServiceProvider;
use Core\Repository\IUserRepository;

class ServiceProvider implements IServiceProvider
{

	public function getServices()
	{
		return [
			IUserRepository::class => UserRepository::class,
			IDatabaseConfiguration::class => DatabaseConfiguration::class,
			ArticleRepository::class => ArticleRepository::class,
		];
	}
}