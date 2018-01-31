<?php
namespace Test\Unit\Controller\Game;

use Controller\Game\ListGame;
use Model\Grid;
use Model\Grid\Loader;
use Model\UrlBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\Game;
use Symfony\Component\HttpFoundation\Request;
use Wonders\GamePlayer;
use Wonders\Player;

class ListGameTest extends TestCase
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
     * @var Game | MockObject
     */
    private $gameService;
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;

    /**
     * @var UrlBuilder | MockObject
     */
    private $urlBuilder;
    /**
     * @var ListGame
     */
    private $controller;

    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->request      = $this->createMock(Request::class);
        $this->gridLoader   = $this->createMock(Loader::class);
        $this->twig         = $this->createMock(\Twig_Environment::class);
        $this->gameService  = $this->createMock(Game::class);
        $this->urlBuilder   = $this->createMock(UrlBuilder::class);
        $this->controller   = new ListGame(
            $this->request,
            $this->gridLoader,
            $this->twig,
            $this->gameService,
            $this->urlBuilder
        );
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->request      = null;
        $this->gridLoader   = null;
        $this->twig         = null;
        $this->gameService  = null;
        $this->urlBuilder   = null;
        $this->controller   = null;
        parent::tearDown();
    }

    /**
     * @tests ListGame::execute check memoizing
     */
    public function testExecute()
    {
        $grid = $this->createMock(Grid::class);
        $game = $this->createMock(\Wonders\Game::class);
        $game->method('getGamePlayers')->willReturn([]);
        $this->gridLoader->method('loadGrid')->willReturn($grid);
        $this->gameService->expects($this->once())->method('getGames')->willReturn([$game]);
        $this->controller->execute();
        $this->controller->execute();
    }

    /**
     * @tests ListGame::execute check url builder calls
     */
    public function testExecuteUrlBuilder()
    {
        $grid = $this->createMock(Grid::class);
        $game = $this->createMock(\Wonders\Game::class);
        $gamePlayer = $this->createMock(GamePlayer::class);
        $player = $this->createMock(Player::class);
        $gamePlayer->method('getPlayer')->willReturn($player);
        $game->method('getGamePlayers')->willReturn([$gamePlayer, $gamePlayer]);
        $this->gridLoader->method('loadGrid')->willReturn($grid);
        $this->gameService->expects($this->once())->method('getGames')->willReturn([$game]);
        $this->urlBuilder->expects($this->exactly(2))->method('getUrl');
        $this->controller->execute();
    }
}
