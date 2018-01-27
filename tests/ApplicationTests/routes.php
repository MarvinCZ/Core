<?php

/** @var \Core\Router $router */
$router = $this;

$router->addGet('/', \Tests\ApplicationTests\TestController::class, 'index');
