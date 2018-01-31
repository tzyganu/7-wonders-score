<?php
namespace Test\Unit\Controller\Game;

use Controller\Game\NewGame;
use Model\Side;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Collection\CollectionIterator;
use Propel\Runtime\Collection\ObjectCollection;
use Service\Category;
use Service\Player;
use Service\Wonder;
use Service\WonderGroup;
use Symfony\Component\HttpFoundation\Request;

class NewGameTest extends TestCase
{
    /**
     * @var Request | MockObject
     */
    private $request;
    /**
     * @var \Service\Category | MockObject
     */
    private $categoryService;
    /**
     * @var Player | MockObject
     */
    private $playerService;
    /**
     * @var Wonder | MockObject
     */
    private $wonderService;
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
    /**
     * @var Side | MockObject
     */
    private $side;
    /**
     * @var WonderGroup | MockObject
     */
    private $wonderGroupService;
    /**
     * @var NewGame | MockObject
     */
    private $controller;

    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->request            = $this->createMock(Request::class);
        $this->categoryService    = $this->createMock(Category::class);
        $this->playerService      = $this->createMock(Player::class);
        $this->twig               = $this->createMock(\Twig_Environment::class);
        $this->wonderService      = $this->createMock(Wonder::class);
        $this->side               = $this->createMock(Side::class);
        $this->wonderGroupService = $this->createMock(WonderGroup::class);
        $this->controller         = new NewGame(
            $this->request,
            $this->categoryService,
            $this->playerService,
            $this->wonderService,
            $this->twig,
            $this->side,
            $this->wonderGroupService
        );
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->request            = null;
        $this->categoryService    = null;
        $this->playerService      = null;
        $this->twig               = null;
        $this->wonderService      = null;
        $this->side               = null;
        $this->wonderGroupService = null;
        $this->controller         = null;
        parent::tearDown();
    }

    /**
     * @tests NewGame::execute
     */
    public function testExecute()
    {
        $propelCollection = $this->createMock(ObjectCollection::class);
        $iterator = $this->createMock(CollectionIterator::class);
        $propelCollection->method('getIterator')->willReturn($iterator);
        $this->wonderService->expects($this->once())->method('getWonders')->willReturn($propelCollection);
        $this->playerService->expects($this->once())->method('getPlayers')->willReturn($propelCollection);
        $this->categoryService->expects($this->once())->method('getCategories')->willReturn($propelCollection);
        $this->side->expects($this->once())->method('getSides')->willReturn([]);
        $this->twig->expects($this->once())->method('render')->willReturn('content');
        $this->assertEquals('content', $this->controller->execute());
    }
}
