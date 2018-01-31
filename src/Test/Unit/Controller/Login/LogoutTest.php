<?php
namespace Test\Unit\Controller\Login;

use Controller\Login\Logout;
use Model\FlashMessage;
use Model\ResponseFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class LogoutTest extends TestCase
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
     * @var FlashMessage | MockObject
     */
    private $flashMessage;
    /**
     * @var ResponseFactory | MockObject
     */
    private $responseFactory;
    /**
     * @var Logout
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
        $this->flashMessage     = $this->createMock(FlashMessage::class);
        $this->responseFactory  = $this->createMock(ResponseFactory::class);
        $this->controller       = new Logout(
            $this->session,
            $this->request,
            $this->responseFactory,
            $this->flashMessage
        );
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->request          = null;
        $this->session          = null;
        $this->flashMessage     = null;
        $this->responseFactory  = null;
        $this->controller       = null;
        parent::tearDown();
    }

    /**
     * @tests Logout::execute
     */
    public function testExecute()
    {
        $this->session->expects($this->once())->method('set');
        $this->flashMessage->expects($this->once())->method('addSuccessMessage');
        $this->responseFactory->expects($this->once())->method('create')->willReturn('redirect');
        $this->request->expects($this->once())->method('getBaseUrl');
        $this->assertEquals('redirect', $this->controller->execute());
    }
}
