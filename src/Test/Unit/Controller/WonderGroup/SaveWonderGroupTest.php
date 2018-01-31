<?php
namespace Test\Unit\Controller\WonderGroup;

use Controller\WonderGroup\SaveWonderGroup;
use Model\Factory\WonderGroupFactory;
use Model\Factory\WonderGroupWonderFactory;
use Model\FlashMessage;
use Model\ResponseFactory;
use Model\Transaction;
use Model\UrlBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\WonderGroup;
use Service\WonderGroupWonder;
use Symfony\Component\HttpFoundation\Request;

class SaveWonderGroupTest extends TestCase
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
     * @var WonderGroupWonder | MockObject
     */
    private $wonderGroupWonderService;
    /**
     * @var WonderGroupWonderFactory | MockObject
     */
    private $wonderGroupWonderFactory;
    /**
     * @var Transaction | MockObject
     */
    private $transaction;
    /**
     * @var SaveWonderGroup
     */
    private $controller;

    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->request                  = $this->createMock(Request::class);
        $this->wonderGroupFactory       = $this->createMock(WonderGroupFactory::class);
        $this->wonderGroupService       = $this->createMock(WonderGroup::class);
        $this->responseFactory          = $this->createMock(ResponseFactory::class);
        $this->flashMessage             = $this->createMock(FlashMessage::class);
        $this->urlBuilder               = $this->createMock(UrlBuilder::class);
        $this->wonderGroupWonderService = $this->createMock(WonderGroupWonder::class);
        $this->wonderGroupWonderFactory = $this->createMock(WonderGroupWonderFactory::class);
        $this->transaction              = $this->createMock(Transaction::class);
        $this->controller           = new SaveWonderGroup(
            $this->request,
            $this->responseFactory,
            $this->wonderGroupService,
            $this->wonderGroupFactory,
            $this->urlBuilder,
            $this->flashMessage,
            $this->wonderGroupWonderService,
            $this->wonderGroupWonderFactory,
            $this->transaction
        );
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->request                  = null;
        $this->wonderGroupFactory       = null;
        $this->wonderGroupService       = null;
        $this->responseFactory          = null;
        $this->flashMessage             = null;
        $this->urlBuilder               = null;
        $this->controller               = null;
        $this->wonderGroupWonderService = null;
        $this->wonderGroupWonderFactory = null;
        $this->transaction              = null;
        parent::tearDown();
    }

    /**
     * @tests SaveWonderGroup::execute on edit mode
     */
    public function testExecuteEdit()
    {
        $this->request->method('get')->willReturn('1');
        $wonderGroup = $this->createMock(\Wonders\WonderGroup::class);
        $wonderRelation = $this->createMock(\Wonders\WonderGroupWonder::class);
        $wonderGroup->method('getWonderGroupWonders')->willReturn([$wonderRelation]);
        $this->wonderGroupWonderFactory->method('create')->willReturn($wonderRelation);
        $this->wonderGroupService->method('getWonderGroup')->willReturn($wonderGroup);
        $this->wonderGroupWonderService->expects($this->once())->method('delete')->willReturn(null);
        $this->transaction->expects($this->once())->method('begin');
        $this->transaction->expects($this->once())->method('commit');
        $this->flashMessage->expects($this->once())->method('addSuccessMessage');
        $this->wonderGroupService->expects($this->once())->method('save');
        $this->responseFactory->method('create')->willReturn('response');
        $this->assertEquals('response', $this->controller->execute());
    }

    /**
     * @tests SaveWonderGroup::execute on edit mode with not valid wonderGroup
     */
    public function testExecuteEditError()
    {
        $this->request->method('get')->willReturn('1');
        $this->wonderGroupService->method('getWonderGroup')->willReturn(null);
        $this->flashMessage->expects($this->once())->method('addErrorMessage');
        $this->responseFactory->method('create')->willReturn('response');
        $this->assertEquals('response', $this->controller->execute());
    }

    /**
     * @tests SaveWonderGroup::execute on edit mode
     */
    public function testExecuteNew()
    {
        $this->request->method('get')->willReturn(null);
        $wonderGroup = $this->createMock(\Wonders\WonderGroup::class);
        $this->wonderGroupFactory->expects($this->once())->method('create')->willReturn($wonderGroup);
        $this->flashMessage->expects($this->once())->method('addSuccessMessage');
        $this->wonderGroupService->expects($this->once())->method('save');
        $this->responseFactory->method('create')->willReturn('response');
        $this->assertEquals('response', $this->controller->execute());
    }
}
