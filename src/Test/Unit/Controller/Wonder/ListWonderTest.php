<?php
namespace Test\Unit\Controller\Wonder;

use Controller\Wonder\ListWonder;
use Model\Grid;
use Model\Grid\Loader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\Wonder;
use Symfony\Component\HttpFoundation\Request;

class ListWonderTest extends TestCase
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
     * @var Wonder | MockObject
     */
    private $wonderService;
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
    /**
     * @var ListWonder
     */
    private $controller;

    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->request       = $this->createMock(Request::class);
        $this->gridLoader    = $this->createMock(Loader::class);
        $this->twig          = $this->createMock(\Twig_Environment::class);
        $this->wonderService = $this->createMock(Wonder::class);
        $this->controller    = new ListWonder(
            $this->request,
            $this->gridLoader,
            $this->twig,
            $this->wonderService
        );
    }

    /**
     * cleanup after tests
     */
    public function tearDown()
    {
        $this->request       = null;
        $this->gridLoader    = null;
        $this->twig          = null;
        $this->wonderService = null;
        $this->controller    = null;
        parent::tearDown();
    }

    /**
     * @tests ListWonder::execute check memoizing
     */
    public function testExecute()
    {
        $grid = $this->createMock(Grid::class);
        $this->gridLoader->method('loadGrid')->willReturn($grid);
        $this->wonderService->expects($this->once())->method('getWonders');
        $this->controller->execute();
        $this->controller->execute();
    }
}
