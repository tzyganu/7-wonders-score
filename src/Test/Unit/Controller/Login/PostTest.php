<?php
namespace Test\Unit\Controller\Login;

use Controller\Login\Post;
use Model\FlashMessage;
use Model\Hash;
use Model\ResponseFactory;
use Model\UrlBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class PostTest extends TestCase
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
     * @var Hash | MockObject
     */
    private $hash;
    /**
     * @var User | MockObject
     */
    private $userService;
    /**
     * @var ResponseFactory | MockObject
     */
    private $responseFactory;
    /**
     * @var FlashMessage | MockObject
     */
    private $flashMessage;
    /**
     * @var UrlBuilder | MockObject
     */
    private $urlBuilder;
    /**
     * @var Post
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
        $this->hash             = $this->createMock(Hash::class);
        $this->responseFactory  = $this->createMock(ResponseFactory::class);
        $this->userService      = $this->createMock(User::class);
        $this->flashMessage     = $this->createMock(FlashMessage::class);
        $this->urlBuilder       = $this->createMock(UrlBuilder::class);
        $this->controller       = new Post(
            $this->request,
            $this->session,
            $this->hash,
            $this->userService,
            $this->responseFactory,
            $this->flashMessage,
            $this->urlBuilder
        );
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->request          = null;
        $this->session          = null;
        $this->hash             = null;
        $this->responseFactory  = null;
        $this->userService      = null;
        $this->flashMessage     = null;
        $this->urlBuilder       = null;
        $this->controller       = null;
        parent::tearDown();
    }

    /**
     * @tests Post::execute when user is logged in
     */
    public function testExecuteLoggedIn()
    {
        $this->session->expects($this->once())->method('get')->willReturn('user');
        $this->urlBuilder->expects($this->once())->method('getUrl');
        $this->responseFactory->expects($this->once())->method('create');
        $this->controller->execute();
    }

    /**
     * @tests Post::execute with empty credentials
     */
    public function testExecuteWrongData()
    {
        $this->session->expects($this->once())->method('get')->willReturn(null);
        $this->request->method('get')->willReturn(null);
        $this->flashMessage->expects($this->once())->method('addErrorMessage');
        $this->controller->execute();
    }

    /**
     * @tests Post::execute when user does not exist
     */
    public function testExecuteNoUser()
    {
        $this->session->expects($this->once())->method('get')->willReturn(null);
        $this->request->method('get')->willReturn('not-null');
        $this->userService->expects($this->once())->method('getUserByUsername')->willReturn(null);
        $this->flashMessage->expects($this->once())->method('addErrorMessage');
        $this->controller->execute();
    }

    /**
     * @tests Post::execute when user does not exist
     */
    public function testExecuteDisabledUser()
    {
        $this->session->expects($this->once())->method('get')->willReturn(null);
        $this->request->method('get')->willReturn('not-null');
        $user = $this->createMock(\Wonders\User::class);
        $user->method('getActive')->willReturn(\Wonders\User::STATUS_INACTIVE);
        $this->userService->expects($this->once())->method('getUserByUsername')->willReturn($user);
        $this->flashMessage->expects($this->once())->method('addErrorMessage');
        $this->controller->execute();
    }

    /**
     * @tests Post::execute when user does not exist
     */
    public function testExecuteRightCredentials()
    {
        $this->session->expects($this->once())->method('get')->willReturn(null);
        $this->request->method('get')->willReturn('not-null');
        $user = $this->createMock(\Wonders\User::class);
        $user->method('getActive')->willReturn(\Wonders\User::STATUS_ACTIVE);
        $user->method('getPassword')->willReturn("not-null");
        $this->hash->method('hash')->willReturn('not-null');
        $this->userService->expects($this->once())->method('getUserByUsername')->willReturn($user);
        $this->session->expects($this->once())->method('set');
        $this->flashMessage->expects($this->once())->method('addSuccessMessage');
        $this->responseFactory->expects($this->once())->method('create')->willReturn('redirect');
        $this->assertEquals('redirect', $this->controller->execute());
    }
}
