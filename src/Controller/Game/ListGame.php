<?php
namespace Controller\Game;

use Controller\ControllerInterface;
use Controller\GridController;
use Model\Grid;
use Model\UrlBuilder;
use Service\Game;
use Symfony\Component\HttpFoundation\Request;

class ListGame extends GridController implements ControllerInterface
{
    const GRID_NAME = 'game';
    /**
     * @var Game
     */
    private $gameService;
    /**
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * ListGame constructor.
     * @param Request $request
     * @param Grid\Loader $gridLoader
     * @param \Twig_Environment $twig
     * @param Game $gameService
     * @param UrlBuilder $urlBuilder
     * @param string $template
     * @param string $pageTitle
     * @param array $selectedMenu
     */
    public function __construct(
        Request $request,
        Grid\Loader $gridLoader,
        \Twig_Environment $twig,
        Game $gameService,
        UrlBuilder $urlBuilder,
        $template = '',
        $pageTitle = '',
        array $selectedMenu = []
    ) {
        $this->gameService = $gameService;
        $this->urlBuilder  = $urlBuilder;
        parent::__construct($request, $gridLoader, $twig, $template, $pageTitle, $selectedMenu);
    }

    /**
     * @return array
     */
    protected function getRows()
    {
        $rows = [];
        $games = $this->gameService->getGames();
        foreach ($games as $game) {
            $row = [];
            /** @var \Wonders\Game $game  */
            $row['id'] = $game->getId();
            $row['date'] = $game->getDate('Y-m-d');
            $playerNames = [];
            $total = 0;
            $winners = [];
            foreach ($game->getGamePlayers() as $gamePlayer) {
                $name = '<a href="'.$this->urlBuilder->getUrl('player/edit', ['id' => $gamePlayer->getPlayerId()])
                    .'">'.$gamePlayer->getPlayer()->getName().'</a>';
                $playerNames[] = $name;
                if ($gamePlayer->getPlace() == 1) {
                    $winners[] = $name;
                }
                $total += $gamePlayer->getPoints();
            }
            $row['player_count'] = $game->getPlayerCount();
            $row['player_names'] = implode(', ', $playerNames);
            $row['winner'] = implode(', ', $winners);
            $row['total'] = $total;
            $row['average'] = (count($playerNames)) ?  $total / count($playerNames) : 0;
            $rows[] = $row;
        }
        return $rows;
    }
}
