<?php
namespace Test\Unit\Controller\Game;

use Controller\Game\SaveGame;
use Model\FlashMessage;
use Model\GameManager;
use Model\ResponseFactory;
use Model\UrlBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Wonders\Game;
use Wonders\User;

class SaveGameTest extends TestCase
{
    /**
     * @var Session | MockObject
     */
    private $session;
    /**
     * @var Request | MockObject
     */
    private $request;
    /**
     * @var FlashMessage | MockObject
     */
    private $flashMessage;
    /**
     * @var ResponseFactory | MockObject
     */
    private $responseFactory;
    /**
     * @var UrlBuilder | MockObject
     */
    private $urlBuilder;
    /**
     * @var GameManager | MockObject
     */
    private $gameManager;
    /**
     * @var SaveGame
     */
    private $controller;

    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->session          = $this->createMock(Session::class);
        $this->request          = $this->createMock(Request::class);
        $this->flashMessage     = $this->createMock(FlashMessage::class);
        $this->responseFactory  = $this->createMock(ResponseFactory::class);
        $this->urlBuilder       = $this->createMock(UrlBuilder::class);
        $this->gameManager      = $this->createMock(GameManager::class);
        $this->controller       = new SaveGame(
            $this->request,
            $this->session,
            $this->flashMessage,
            $this->responseFactory,
            $this->urlBuilder,
            $this->gameManager
        );
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->session          = null;
        $this->request          = null;
        $this->flashMessage     = null;
        $this->responseFactory  = null;
        $this->urlBuilder       = null;
        $this->gameManager      = null;
        $this->controller       = null;
        parent::tearDown();
    }

    /**
     * @tests SaveGame::execute()
     */
    public function testExecute()
    {
        $this->session->method('get')->with('user')->willReturn($this->createMock(User::class));
        $this->request->method('get')->willReturn([]);
        $game = $this->createMock(Game::class);
        $this->gameManager->expects($this->once())->method('saveGame')->willReturn($game);
        $this->flashMessage->expects($this->once())->method('addSuccessMessage');
        $this->urlBuilder->expects($this->once())->method('getUrl');
        $this->responseFactory->expects($this->once())->method('create')->willReturn('redirect');
        $this->assertEquals('redirect', $this->controller->execute());
    }

    /**
     * @tests SaveGame::execute() when save fails
     */
    public function testExecuteSaveProblem()
    {
        $this->session->method('get')->with('user')->willReturn($this->createMock(User::class));
        $this->request->method('get')->willReturn([]);
        $game = $this->createMock(Game::class);
        $this->gameManager->expects($this->once())->method('saveGame')->willThrowException(new \Exception());
        $this->flashMessage->expects($this->once())->method('addErrorMessage');
        $this->urlBuilder->expects($this->once())->method('getUrl');
        $this->responseFactory->expects($this->once())->method('create')->willReturn('redirect');
        $this->assertEquals('redirect', $this->controller->execute());
    }
}
