<?php
namespace Test\Unit\Controller\Login;

use Controller\Login\Index;
use Model\ResponseFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class IndexTest extends TestCase
{
    /**
     * @var Request | MockObject
     */
    private $request;
    /**
     * @var Session | MockObject
     */
    private $session;
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
    /**
     * @var ResponseFactory | MockObject
     */
    private $responseFactory;
    /**
     * @var Index
     */
    private $controller;

    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->request          = $this->createMock(Request::class);
        $this->session          = $this->createMock(Session::class);
        $this->twig             = $this->createMock(\Twig_Environment::class);
        $this->responseFactory  = $this->createMock(ResponseFactory::class);
        $this->controller       = new Index(
            $this->request,
            $this->session,
            $this->twig,
            $this->responseFactory
        );
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->request          = null;
        $this->session          = null;
        $this->twig             = null;
        $this->responseFactory  = null;
        $this->controller       = null;
        parent::tearDown();
    }

    /**
     * @tests Index::execute when user is not logged in
     */
    public function testExecuteNotLoggedIn()
    {
        $this->session->expects($this->once())->method('get')->willReturn(null);
        $this->request->expects($this->exactly(0))->method('getBaseUrl');
        $this->responseFactory->expects($this->exactly(0))->method('create');
        $this->twig->expects($this->once())->method('render')->willReturn('content');
        $this->assertEquals('content', $this->controller->execute());
    }

    /**
     * @tests Index::execute when user is already logged in
     */
    public function testExecuteLoggedIn()
    {
        $this->session->expects($this->once())->method('get')->willReturn('not-null');
        $this->request->expects($this->once())->method('getBaseUrl');
        $this->responseFactory->expects($this->once())->method('create')->willReturn('redirect');
        $this->twig->expects($this->exactly(0))->method('render');
        $this->assertEquals('redirect', $this->controller->execute());
    }
}
