<?php

namespace Core\Controller;

use Core\Exceptions\AlreadyRendered;
use Core\Http\Request;

class BaseController
{
    private $rendered = false;
    private $request;

    protected function render(string $output)
    {
        if ($this->isRendered()) {
            throw new AlreadyRendered();
        }

        echo $output;
        $this->rendered = true;
    }

    protected function renderJSON($data)
    {
        $this->render(json_encode($data));
    }

    protected function renderView($name, $params)
    {
    }

    protected function isAJAXRequest()
    {
        return strpos($_SERVER['HTTP_ACCEPT'], 'text/javascript') !== false ||
            preg_match('/^[\/]js[\/]/', $_SERVER['REQUEST_URI']);
    }

    /**
     * @return bool
     */
    public function isRendered(): bool
    {
        return $this->rendered;
    }

    public function injectRequest(Request $request)
    {
        $this->request = $request;
    }
}
