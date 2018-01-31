<?php
namespace Test\Unit\Controller\Game;

use Controller\Game\ViewGame;
use Model\FlashMessage;
use Model\Grid;
use Model\Grid\Button\Factory;
use Model\ResponseFactory;
use Model\UrlBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\Game;
use Symfony\Component\HttpFoundation\Request;
use Wonders\Category;
use Wonders\GameCategory;
use Wonders\GamePlayer;
use Wonders\Player;
use Wonders\Score;

class ViewGameTest extends TestCase
{
    /**
     * @var \Model\Grid\Factory | MockObject
     */
    private $gridFactory;
    /**
     * @var Factory  | MockObject
     */
    private $buttonFactory;
    /**
     * @var \Model\Grid\Column\Factory  | MockObject
     */
    private $columnFactory;
    /**
     * @var Game  | MockObject
     */
    private $gameService;
    /**
     * @var Request  | MockObject
     */
    private $request;
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
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
     * @var ViewGame
     */
    private $controller;

    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->gridFactory     = $this->createMock(\Model\Grid\Factory::class);
        $this->buttonFactory   = $this->createMock(Factory::class);
        $this->columnFactory   = $this->createMock(\Model\Grid\Column\Factory::class);
        $this->gameService     = $this->createMock(Game::class);
        $this->request         = $this->createMock(Request::class);
        $this->twig            = $this->createMock(\Twig_Environment::class);
        $this->responseFactory = $this->createMock(ResponseFactory::class);
        $this->flashMessage    = $this->createMock(FlashMessage::class);
        $this->urlBuilder      = $this->createMock(UrlBuilder::class);

        $this->controller      = new ViewGame(
            $this->request,
            $this->twig,
            $this->gridFactory,
            $this->columnFactory,
            $this->buttonFactory,
            $this->gameService,
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
        $this->gridFactory     = null;
        $this->buttonFactory   = null;
        $this->columnFactory   = null;
        $this->gameService     = null;
        $this->request         = null;
        $this->twig            = null;
        $this->responseFactory = null;
        $this->flashMessage    = null;
        $this->urlBuilder      = null;
        $this->controller      = null;
        parent::tearDown();
    }

    /**
     * tests ViewGame::execute
     */
    public function testExecute()
    {
        $game = $this->createMock(\Wonders\Game::class);
        $grid = $this->createMock(Grid::class);
        $column = $this->createMock(Grid\Column::class);
        $button = $this->createMock(Grid\Button::class);
        $gameCategory = $this->createMock(GameCategory::class);
        $category = $this->createMock(Category::class);
        $gameCategory->method('getCategory')->willReturn($category);

        $gamePlayer = $this->createMock(GamePlayer::class);
        $player = $this->createMock(Player::class);
        $gamePlayer->method('getPlayer')->willReturn($player);
        $game->expects($this->once())->method('getGamePlayers')->willReturn([$gamePlayer, $gamePlayer]);

        $score = $this->createMock(Score::class);
        $game->method('getScores')->willReturn([$score, $score]);

        $game->expects($this->once())->method('getGameCategories')->willReturn([$gameCategory, $gameCategory]);
        $this->columnFactory->expects($this->exactly(5))->method('create')->willReturn($column);
        $this->buttonFactory->expects($this->once())->method('create')->willReturn($button);
        $this->request->method('get')->willReturn(1);
        $this->gameService->method('getGame')->willReturn($game);
        $this->gridFactory->method('create')->willReturn($grid);
        $this->twig->expects($this->once())->method('render')->willReturn('content');
        $this->flashMessage->expects($this->exactly(0))->method('addErrorMessage');
        $this->urlBuilder->expects($this->exactly(0))->method('getUrl');
        $this->responseFactory->expects($this->exactly(0))->method('create');
        $this->assertEquals('content', $this->controller->execute());
    }

    /**
     * tests ViewGame::execute with wrong game id
     */
    public function testExecuteWongGame()
    {
        $this->request->method('get')->willReturn(null);
        $this->flashMessage->expects($this->once())->method('addErrorMessage');
        $this->urlBuilder->expects($this->once())->method('getUrl');
        $this->responseFactory->expects($this->once())->method('create')->willReturn('redirect');
        $this->assertEquals('redirect', $this->controller->execute());
    }
}
