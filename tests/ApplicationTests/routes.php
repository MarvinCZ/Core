<?php

/** @var \Core\Router $router */
$router = $this;

$router->addGet('/news', \Tests\ApplicationTests\TestController::class, 'index');
$router->addGet('/news/{id:int}', \Tests\ApplicationTests\TestController::class, 'show');
