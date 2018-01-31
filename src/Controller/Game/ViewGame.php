<?php
namespace Controller\Game;

use Controller\ControllerInterface;
use Model\FlashMessage;
use Model\Grid;
use Model\Grid\Factory as GridFactory;
use Model\Grid\Column\Factory as ColumnFactory;
use Model\Grid\Button\Factory as ButtonFactory;
use Model\ResponseFactory;
use Model\UrlBuilder;
use Symfony\Component\HttpFoundation\Request;
use Wonders\Game;
use Wonders\Score;

class ViewGame implements ControllerInterface
{
    /**
     * @var Grid
     */
    private $grid;
    /**
     * @var Game
     */
    private $game;
    /**
     * @var GridFactory
     */
    private $gridFactory;
    /**
     * @var ButtonFactory
     */
    private $buttonFactory;
    /**
     * @var ColumnFactory
     */
    private $columnFactory;
    /**
     * @var \Service\Game
     */
    private $gameService;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var string
     */
    private $template;
    /**
     * @var array
     */
    private $selectedMenu;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;
    /**
     * @var UrlBuilder
     */
    private $urlBuilder;
    /**
     * @var FlashMessage
     */
    private $flashMessage;

    /**
     * ViewGame constructor.
     * @param Request $request
     * @param \Twig_Environment $twig
     * @param GridFactory $gridFactory
     * @param ColumnFactory $columnFactory
     * @param ButtonFactory $buttonFactory
     * @param \Service\Game $gameService
     * @param ResponseFactory $responseFactory
     * @param FlashMessage $flashMessage
     * @param UrlBuilder $urlBuilder
     * @param string $template
     * @param array $selectedMenu
     */
    public function __construct(
        Request $request,
        \Twig_Environment $twig,
        GridFactory $gridFactory,
        ColumnFactory $columnFactory,
        ButtonFactory $buttonFactory,
        \Service\Game $gameService,
        ResponseFactory $responseFactory,
        FlashMessage $flashMessage,
        UrlBuilder $urlBuilder,
        $template = '',
        array $selectedMenu = []
    ) {
        $this->gridFactory      = $gridFactory;
        $this->buttonFactory    = $buttonFactory;
        $this->columnFactory    = $columnFactory;
        $this->gameService      = $gameService;
        $this->request          = $request;
        $this->twig             = $twig;
        $this->responseFactory  = $responseFactory;
        $this->flashMessage     = $flashMessage;
        $this->urlBuilder       = $urlBuilder;
        $this->template         = $template;
        $this->selectedMenu     = $selectedMenu;
    }

    /**
     * @return string
     */
    public function execute()
    {
        $game = $this->getGame();
        if ($this->getGame() === null) {
            $this->flashMessage->addErrorMessage("The game you are looking for does not exist");
            return $this->responseFactory->create(
                ResponseFactory::REDIRECT,
                ['url' => $this->urlBuilder->getUrl('game/list')]
            );
        }
        return $this->twig->render(
            $this->template,
            [
                'page_title' => 'View game '.$game->getId().': '.$game->getDate('Y-m-d'),
                'selectedMenu' => $this->selectedMenu,
                'content' => $this->getGrid()->render()
            ]
        );
    }

    /**
     * @return Game
     */
    private function getGame()
    {
        if ($this->game === null) {
            $id = $this->request->get('id');
            if ($id) {
                $this->game = $this->gameService->getGame($id);
            }
        }
        return $this->game;
    }

    /**
     * @return array
     */
    private function getGameScores()
    {
        $game = $this->getGame();
        $scoresByPlayer = [];
        $scores = $game->getScores();
        foreach ($scores as $score) {
            /** @var Score $score */
            $scoresByPlayer[$score->getPlayerId()][$score->getCategoryId()] = $score->getValue();
        }
        return $scoresByPlayer;
    }

    /**
     * @return array
     */
    private function getRows()
    {
        $game = $this->getGame();
        $rows = [];
        $scores = $this->getGameScores();
        foreach ($game->getGamePlayers() as $gamePlayer) {
            $playerId = $gamePlayer->getPlayer()->getId();
            $name = $gamePlayer->getPlayer()->getName();
            if ($gamePlayer->getWonderId()) {
                $name .= ': '.$gamePlayer->getWonder()->getName();
            }
            if ($gamePlayer->getSide()) {
                $name .= ' - '.$gamePlayer->getSide();
            }
            $row = [
                'name' => $name,
                'total' => $gamePlayer->getPoints(),
                'rank' => $gamePlayer->getPlace()
            ];
            foreach ($scores[$playerId] as $key => $value) {
                $row[$key] = $value;
            }
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * @return Grid
     */
    private function getGrid()
    {
        if ($this->grid === null) {
            $game = $this->getGame();
            $grid = $this->gridFactory->create([
                'emptyMessage' => 'Something went wrong here',
                'id' => 'game',
                'title' => 'Game '.$game->getId().' : '.$game->getDate('Y-m-d')
            ]);
            $grid->addColumn(
                $this->columnFactory->create([
                    'type' => 'text',
                    'index' => 'name',
                    'label' => ''
                ])
            );
            foreach ($game->getGameCategories() as $category) {
                $grid->addColumn(
                    $this->columnFactory->create([
                        'type' => 'integer',
                        'index' => $category->getCategory()->getId(),
                        'label' => $category->getCategory()->getName(),
                        'iconClass' => $category->getCategory()->getIconClass()
                    ])
                );
            }
            $grid->addColumn(
                $this->columnFactory->create([
                    'type' => 'integer',
                    'index' => 'total',
                    'label' => 'Total',
                ])
            );
            $grid->addColumn(
                $this->columnFactory->create([
                    'type' => 'integer',
                    'index' => 'rank',
                    'label' => 'Rank',
                    'defaultSort' => true
                ])
            );

            $grid->addButton(
                'new',
                $this->buttonFactory->create(['label' => 'Game List', 'url' => 'game/list'])
            );

            $grid->setRows($this->getRows());
            $this->grid = $grid;
        }
        return $this->grid;
    }
}
