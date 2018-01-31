<?php
namespace Test\Unit\Controller\WonderGroup;

use Controller\WonderGroup\ListWonderGroup;
use Model\Grid;
use Model\Grid\Loader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\WonderGroup;
use Symfony\Component\HttpFoundation\Request;

class ListWonderGroupTest extends TestCase
{
    /**
     * @var Request | MockObject
     */
    private $request;
    /**
     * @var Loader | MockObject
     */
    private $gridLoader;
    /**
     * @var WonderGroup | MockObject
     */
    private $wonderGroupService;
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
    /**
     * @var ListWonderGroup
     */
    private $controller;

    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->request              = $this->createMock(Request::class);
        $this->gridLoader           = $this->createMock(Loader::class);
        $this->twig                 = $this->createMock(\Twig_Environment::class);
        $this->wonderGroupService   = $this->createMock(WonderGroup::class);
        $this->controller           = new ListWonderGroup(
            $this->request,
            $this->gridLoader,
            $this->twig,
            $this->wonderGroupService
        );
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->request              = null;
        $this->gridLoader           = null;
        $this->twig                 = null;
        $this->wonderGroupService   = null;
        $this->controller           = null;
        parent::tearDown();
    }

    /**
     * @tests ListWonderGroup::execute check memoizing
     */
    public function testExecute()
    {
        $grid = $this->createMock(Grid::class);
        $this->gridLoader->method('loadGrid')->willReturn($grid);
        $this->wonderGroupService->expects($this->once())->method('getWonderGroups');
        $this->controller->execute();
        $this->controller->execute();
    }
}
