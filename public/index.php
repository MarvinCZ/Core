<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('APP_DIR', __DIR__ . '/..');

$application = new \Core\Application();
$container = $application->getContainer();
$container->addServiceProvider(new \App\Providers\ServiceProvider());
/** @var \Core\Router $router */
$router = $container->getByType(\Core\Router::class);
$router->registerFile(APP_DIR . '/routes/web.php');

$application->run();

