<?php
namespace Test\Unit\Controller\Wonder;

use Controller\Wonder\EditWonder;
use Model\Factory\WonderFactory;
use Model\FlashMessage;
use Model\ResponseFactory;
use Model\UrlBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\Wonder;
use Symfony\Component\HttpFoundation\Request;

class EditWonderTest extends TestCase
{
    /**
     * @var Request | MockObject
     */
    private $request;
    /**
     * @var WonderFactory | MockObject
     */
    private $wonderFactory;
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
    /**
     * @var Wonder | MockObject
     */
    private $wonderService;
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
     * @var EditWonder
     */
    private $controller;

    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->request          = $this->createMock(Request::class);
        $this->wonderFactory    = $this->createMock(WonderFactory::class);
        $this->twig             = $this->createMock(\Twig_Environment::class);
        $this->wonderService    = $this->createMock(Wonder::class);
        $this->responseFactory  = $this->createMock(ResponseFactory::class);
        $this->flashMessage     = $this->createMock(FlashMessage::class);
        $this->urlBuilder       = $this->createMock(UrlBuilder::class);
        $this->controller       = new EditWonder(
            $this->request,
            $this->twig,
            $this->wonderService,
            $this->wonderFactory,
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
        $this->wonderFactory    = null;
        $this->wonderService    = null;
        $this->responseFactory  = null;
        $this->flashMessage     = null;
        $this->urlBuilder       = null;
        $this->twig             = null;
        $this->controller       = null;
        parent::tearDown();
    }


    /**
     * @tests EditWonder::execute on edit mode
     */
    public function testExecuteEdit()
    {
        $this->request->method('get')->willReturn('1');
        $wonder = $this->createMock(\Wonders\Wonder::class);
        $this->wonderService->method('getWonder')->willReturn($wonder);
        $this->twig->method('render')->willReturn('content');
        $this->assertEquals('content', $this->controller->execute());
    }

    /**
     * @tests EditWonder::execute on edit mode with not valid wonder
     */
    public function testExecuteEditError()
    {
        $this->request->method('get')->willReturn('1');
        $this->wonderService->method('getWonder')->willReturn(null);
        $this->flashMessage->expects($this->once())->method('addErrorMessage');
        $this->responseFactory->method('create')->willReturn('redirect');
        $this->assertEquals('redirect', $this->controller->execute());
    }

    /**
     * @tests EditWonder::execute on edit mode
     */
    public function testExecuteNew()
    {
        $this->request->method('get')->willReturn(null);
        $wonder = $this->createMock(\Wonders\Wonder::class);
        $this->wonderFactory->expects($this->once())->method('create')->willReturn($wonder);
        $this->twig->method('render')->willReturn('content');
        $this->assertEquals('content', $this->controller->execute());
    }
}
