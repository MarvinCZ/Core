<?php

namespace Core;

use Core\Http\Request;

class Route
{
    private $method;
    private $url;
    private $controller;
    private $action;
    private $pattern;

    private $paramNames;
    private $params;

    public function __construct($method, $url, $controller, $action)
    {
        $this->method = $method;
        $this->url = $url;
        $this->controller = $controller;
        $this->action = $action;

        $this->generatePattern();
    }

    public function matches(Request $request)
    {
        if ($this->method != $request->getMethod()) {
            return false;
        }
        if (!preg_match_all($this->pattern, $request->getUrl(), $matches)) {
            return false;
        }
        array_shift($matches);
        foreach ($matches as $key => $param) {
            $this->params[$this->paramNames[$key]] = $param[0];
        }
        return true;
    }

    private function generatePattern()
    {
        $this->pattern = '/^' . str_replace('/', '\/', $this->url) . '$/';
        $this->pattern = preg_replace_callback('/{[a-zA-Z_-]+:[a-z]+}/', function ($match) {
            $param = substr($match[0], 1, strlen($match[0]) - 2);
            $parts = explode(':', $param);
            $this->paramNames []= $parts[0];
            return '(' . $this->getRegex($parts[1]) . ')';
        }, $this->pattern);
    }

    private function getRegex($type)
    {
        if ($type == 'int') {
            return '\d+';
        }
        if ($type == 'string') {
            return '\w+';
        }
        return '';
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}