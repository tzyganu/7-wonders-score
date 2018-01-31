<?php
namespace Test\Unit\Controller\Wonder;

use Controller\Wonder\SaveWonder;
use Model\Factory\WonderFactory;
use Model\FlashMessage;
use Model\ResponseFactory;
use Model\UrlBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\Wonder;
use Symfony\Component\HttpFoundation\Request;

class SaveWonderTest extends TestCase
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
     * @var SaveWonder
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
        $this->wonderService    = $this->createMock(Wonder::class);
        $this->responseFactory  = $this->createMock(ResponseFactory::class);
        $this->flashMessage     = $this->createMock(FlashMessage::class);
        $this->urlBuilder       = $this->createMock(UrlBuilder::class);
        $this->controller       = new SaveWonder(
            $this->request,
            $this->responseFactory,
            $this->wonderService,
            $this->wonderFactory,
            $this->urlBuilder,
            $this->flashMessage
        );
    }

    /**
     * cleanup after tests
     */
    public function tearDown()
    {
        $this->request          = null;
        $this->wonderFactory    = null;
        $this->wonderService    = null;
        $this->responseFactory  = null;
        $this->flashMessage     = null;
        $this->urlBuilder       = null;
        $this->controller       = null;
        parent::tearDown();
    }

    /**
     * @tests SaveWonder::execute on edit mode
     */
    public function testExecuteEdit()
    {
        $this->request->method('get')->willReturn('1');
        $wonder = $this->createMock(\Wonders\Wonder::class);
        $this->wonderService->method('getWonder')->willReturn($wonder);
        $this->flashMessage->expects($this->once())->method('addSuccessMessage');
        $this->wonderService->expects($this->once())->method('save');
        $this->responseFactory->method('create')->willReturn('response');
        $this->assertEquals('response', $this->controller->execute());
    }

    /**
     * @tests SaveWonder::execute on edit mode with not valid wonder
     */
    public function testExecuteEditError()
    {
        $this->request->method('get')->willReturn('1');
        $this->wonderService->method('getWonder')->willReturn(null);
        $this->flashMessage->expects($this->once())->method('addErrorMessage');
        $this->responseFactory->method('create')->willReturn('response');
        $this->assertEquals('response', $this->controller->execute());
    }

    /**
     * @tests SaveWonder::execute on edit mode
     */
    public function testExecuteNew()
    {
        $this->request->method('get')->willReturn(null);
        $wonder = $this->createMock(\Wonders\Wonder::class);
        $this->wonderFactory->expects($this->once())->method('create')->willReturn($wonder);
        $this->flashMessage->expects($this->once())->method('addSuccessMessage');
        $this->wonderService->expects($this->once())->method('save');
        $this->responseFactory->method('create')->willReturn('response');
        $this->assertEquals('response', $this->controller->execute());
    }
}
