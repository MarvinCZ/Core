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

$router->addGet('/reviews', \App\Controller\ReviewController::class, 'list');
$router->addPost('/article/{id:int}/add-reviewers', \App\Controller\ReviewController::class, 'addToArticle');
$router->addGet('/review/{id:int}', \App\Controller\ReviewController::class, 'show');
$router->addGet('/review/edit/{id:int}', \App\Controller\ReviewController::class, 'reviewForm');
$router->addPost('/review/edit/{id:int}', \App\Controller\ReviewController::class, 'save');

$router->addGet('/admin/publish-article/{id:int}', \App\Controller\AdminController::class, 'publishArticle');
$router->addGet('/admin/reject-article/{id:int}', \App\Controller\AdminController::class, 'rejectArticle');
$router->addGet('/admin/users', \App\Controller\AdminController::class, 'users');
$router->addGet('/admin/user/{id:int}/{action:string}/{role:string}', \App\Controller\AdminController::class, 'role');
$router->addGet('/admin/articles', \App\Controller\AdminController::class, 'articles');
