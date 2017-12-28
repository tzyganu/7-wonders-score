<?php
namespace Controller\Game;

use Controller\GridController;
use Controller\OutputController;
use Model\Grid;
use Symfony\Component\HttpFoundation\Request;
use Wonders\Game;
use Wonders\GameCategory;
use Wonders\GameQuery;
use Wonders\Score;

class ViewGame extends GridController
{
    /**
     * @var string
     */
    protected $selectedMenu = 'games';
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var Game
     */
    protected $game;

    /**
     * @return Game
     */
    protected function getGame()
    {
        if ($this->game === null) {
            $id = $this->request->get('id');
            if ($id) {
                $this->game = GameQuery::create()
                    ->findOneById($id);
            } else {
                $this->game = new Game();
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
    protected function getRows()
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
    protected function getGrid()
    {
        if ($this->grid === null) {
            $game = $this->getGame();
            $grid = new Grid([
                'emptyMessage' => 'Something went wrong here',
                'id' => 'game',
                'title' => 'Game '.$game->getId().' : '.$game->getDate('Y-m-d')
            ]);
            $grid->addColumn(
                new Grid\Column\Text([
                    'index' => 'name',
                    'label' => ''
                ])
            );
            foreach ($game->getGameCategories() as $category) {
                $grid->addColumn(
                    new Grid\Column\IntegerColumn([
                        'index' => $category->getCategory()->getId(),
                        'label' => $category->getCategory()->getName(),
                        'iconClass' => $category->getCategory()->getIconClass()
                    ])
                );
            }
            $grid->addColumn(
                new Grid\Column\IntegerColumn([
                    'index' => 'total',
                    'label' => 'Total',
                ])
            );
            $grid->addColumn(
                new Grid\Column\IntegerColumn([
                    'index' => 'rank',
                    'label' => 'Rank',
                    'defaultSort' => true
                ])
            );


            $grid->addButton(
                'new',
                new Grid\Button('Game List', $this->request->getBaseUrl().'/game/list')
            );

            $grid->setRows($this->getRows());
            $this->grid = $grid;
        }
        return $this->grid;
    }
}
