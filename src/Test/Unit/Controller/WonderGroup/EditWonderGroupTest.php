<?php
namespace Test\Unit\Controller\WonderGroup;

use Controller\WonderGroup\EditWonderGroup;
use Model\Factory\WonderGroupFactory;
use Model\FlashMessage;
use Model\ResponseFactory;
use Model\UrlBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\Wonder;
use Service\WonderGroup;
use Symfony\Component\HttpFoundation\Request;

class EditWonderGroupTest extends TestCase
{
    /**
     * @var Request | MockObject
     */
    private $request;
    /**
     * @var WonderGroupFactory | MockObject
     */
    private $wonderGroupFactory;
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
    /**
     * @var WonderGroup | MockObject
     */
    private $wonderGroupService;
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
     * @var Wonder | MockObject
     */
    private $wonderService;
    /**
     * @var EditWonderGroup
     */
    private $controller;

    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->request              = $this->createMock(Request::class);
        $this->wonderGroupFactory   = $this->createMock(WonderGroupFactory::class);
        $this->twig                 = $this->createMock(\Twig_Environment::class);
        $this->wonderGroupService   = $this->createMock(WonderGroup::class);
        $this->responseFactory      = $this->createMock(ResponseFactory::class);
        $this->flashMessage         = $this->createMock(FlashMessage::class);
        $this->urlBuilder           = $this->createMock(UrlBuilder::class);
        $this->wonderService        = $this->createMock(Wonder::class);
        $this->controller           = new EditWonderGroup(
            $this->request,
            $this->wonderGroupFactory,
            $this->twig,
            $this->wonderGroupService,
            $this->responseFactory,
            $this->flashMessage,
            $this->urlBuilder,
            $this->wonderService
        );
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->request            = null;
        $this->wonderGroupFactory = null;
        $this->wonderGroupService = null;
        $this->responseFactory    = null;
        $this->flashMessage       = null;
        $this->urlBuilder         = null;
        $this->twig               = null;
        $this->wonderService      = null;
        $this->controller         = null;
        parent::tearDown();
    }


    /**
     * @tests EditWonderGroup::execute on edit mode
     */
    public function testExecuteEdit()
    {
        $this->request->method('get')->willReturn('1');
        $wonderGroup = $this->createMock(\Wonders\WonderGroup::class);
        $this->wonderGroupService->method('getWonderGroup')->willReturn($wonderGroup);
        $this->twig->method('render')->willReturn('content');
        $this->assertEquals('content', $this->controller->execute());
    }

    /**
     * @tests EditWonderGroup::execute on edit mode with not valid wonder Group
     */
    public function testExecuteEditError()
    {
        $this->request->method('get')->willReturn('1');
        $this->wonderGroupService->method('getWonderGroup')->willReturn(null);
        $this->flashMessage->expects($this->once())->method('addErrorMessage');
        $this->responseFactory->method('create')->willReturn('redirect');
        $this->assertEquals('redirect', $this->controller->execute());
    }

    /**
     * @tests EditWonderGroup::execute on edit mode
     */
    public function testExecuteNew()
    {
        $this->request->method('get')->willReturn(null);
        $wonderGroup = $this->createMock(\Wonders\WonderGroup::class);
        $this->wonderGroupFactory->expects($this->once())->method('create')->willReturn($wonderGroup);
        $this->twig->method('render')->willReturn('content');
        $this->assertEquals('content', $this->controller->execute());
    }
}
