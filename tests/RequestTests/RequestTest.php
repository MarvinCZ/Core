<?php

namespace Tests\RequestTests;

use Core\Exceptions\UndefinedParameter;
use Core\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testGetMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $request = new Request();
        $this->assertSame('GET', $request->getMethod());
    }

    public function testPostMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $request = new Request();
        $this->assertSame('POST', $request->getMethod());
    }

    public function testDeleteMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['method'] = 'DELETE';
        $request = new Request();
        $this->assertSame('DELETE', $request->getMethod());
    }

    public function testPostParameterPriority()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['x'] = 'wrong';
        $_POST['x'] = 'right';
        $request = new Request();
        $this->assertSame('right', $request->getParameter('x'));
    }

    public function testPostParameterNotFound()
    {
        $this->expectException(UndefinedParameter::class);
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $request = new Request();
        $request->getParameter('nonexistent');
    }

    public function testGetParameterNotFound()
    {
        $this->expectException(UndefinedParameter::class);
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $request = new Request();
        $request->getParameter('nonexistent');
    }
}
