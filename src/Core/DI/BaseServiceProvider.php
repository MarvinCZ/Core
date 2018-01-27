<?php

namespace Core\DI;

use Core\Database\PdoWrapper;
use Core\Http\Request;
use Core\Router;
use Core\Session;
use Core\View;

class BaseServiceProvider implements IServiceProvider
{

    public function getServices()
    {
        return [
            Request::class => Request::class,
            Router::class => Router::class,
	        View::class => View::class,
	        Session::class => Session::class,
	        PdoWrapper::class => PdoWrapper::class,
        ];
    }
}
