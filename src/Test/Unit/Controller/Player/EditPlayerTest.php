<?php
namespace Test\Unit\Controller\Player;

use Controller\Player\EditPlayer;
use Model\Factory\PlayerFactory;
use Model\FlashMessage;
use Model\ResponseFactory;
use Model\UrlBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\Player;
use Symfony\Component\HttpFoundation\Request;

class EditPlayerTest extends TestCase
{
    /**
     * @var Request | MockObject
     */
    private $request;
    /**
     * @var PlayerFactory | MockObject
     */
    private $playerFactory;
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
    /**
     * @var Player | MockObject
     */
    private $playerService;
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
     * @var EditPlayer
     */
    private $controller;

    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->request          = $this->createMock(Request::class);
        $this->playerFactory    = $this->createMock(PlayerFactory::class);
        $this->twig             = $this->createMock(\Twig_Environment::class);
        $this->playerService    = $this->createMock(Player::class);
        $this->responseFactory  = $this->createMock(ResponseFactory::class);
        $this->flashMessage     = $this->createMock(FlashMessage::class);
        $this->urlBuilder       = $this->createMock(UrlBuilder::class);
        $this->controller       = new EditPlayer(
            $this->request,
            $this->playerFactory,
            $this->playerService,
            $this->twig,
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
        $this->request          = null;
        $this->playerFactory    = null;
        $this->playerService    = null;
        $this->responseFactory  = null;
        $this->flashMessage     = null;
        $this->urlBuilder       = null;
        $this->twig             = null;
        $this->controller       = null;
        parent::tearDown();
    }


    /**
     * @tests EditPlayer::execute on edit mode
     */
    public function testExecuteEdit()
    {
        $this->request->method('get')->willReturn('1');
        $player = $this->createMock(\Wonders\Player::class);
        $this->playerService->method('getPlayer')->willReturn($player);
        $this->twig->method('render')->willReturn('content');
        $this->assertEquals('content', $this->controller->execute());
    }

    /**
     * @tests EditPlayer::execute on edit mode with not valid player
     */
    public function testExecuteEditError()
    {
        $this->request->method('get')->willReturn('1');
        $this->playerService->method('getPlayer')->willReturn(null);
        $this->flashMessage->expects($this->once())->method('addErrorMessage');
        $this->responseFactory->method('create')->willReturn('redirect');
        $this->assertEquals('redirect', $this->controller->execute());
    }

    /**
     * @tests EditPlayer::execute on edit mode
     */
    public function testExecuteNew()
    {
        $this->request->method('get')->willReturn(null);
        $player = $this->createMock(\Wonders\Player::class);
        $this->playerFactory->expects($this->once())->method('create')->willReturn($player);
        $this->twig->method('render')->willReturn('content');
        $this->assertEquals('content', $this->controller->execute());
    }
}
