<?php

/** @var \Core\Router $router */
$router = $this;

$router->addGet('', \App\Controller\HomePageController::class, 'index');
$router->addGet('/news', \App\Controller\HomePageController::class, 'index');
$router->addGet('/login', \App\Controller\LoginController::class, 'loginForm');
$router->addPost('/login', \App\Controller\LoginController::class, 'login');
$router->addGet('/register', \App\Controller\LoginController::class, 'registerForm');
$router->addPost('/register', \App\Controller\LoginController::class, 'register');
$router->addGet('/logout', \App\Controller\LoginController::class, 'logout');

$router->addGet('/my-articles', \App\Controller\ArticleController::class, 'myArticles');
$router->addGet('/article/{id:int}', \App\Controller\ArticleController::class, 'show');
$router->addDelete('/article/{id:int}', \App\Controller\ArticleController::class, 'delete');
$router->addGet('/article-create', \App\Controller\ArticleController::class, 'createForm');
$router->addGet('/article-update/{id:int}', \App\Controller\ArticleController::class, 'updateForm');
$router->addPost('/article-save', \App\Controller\ArticleController::class, 'save');
