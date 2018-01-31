<?php
namespace Test\Unit\Controller\Player;

use Controller\Player\ListPlayer;
use Model\Grid;
use Model\Grid\Loader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\Player;
use Symfony\Component\HttpFoundation\Request;

class ListPlayerTest extends TestCase
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
     * @var Player | MockObject
     */
    private $playerService;
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
    /**
     * @var ListPlayer
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
        $this->playerService = $this->createMock(Player::class);
        $this->controller    = new ListPlayer(
            $this->request,
            $this->gridLoader,
            $this->twig,
            $this->playerService
        );
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->request       = null;
        $this->gridLoader    = null;
        $this->twig          = null;
        $this->playerService = null;
        $this->controller    = null;
        parent::tearDown();
    }

    /**
     * @tests ListPlayer::execute check memoizing
     */
    public function testExecute()
    {
        $grid = $this->createMock(Grid::class);
        $this->gridLoader->method('loadGrid')->willReturn($grid);
        $this->playerService->expects($this->once())->method('getPlayers');
        $this->controller->execute();
        $this->controller->execute();
    }
}
