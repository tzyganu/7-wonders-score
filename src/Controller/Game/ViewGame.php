<?php
namespace Controller\Game;

use Symfony\Component\HttpFoundation\Request;
use Wonders\Game;
use Wonders\GameCategory;
use Wonders\Score;

class ViewGame extends GameController
{
    /**
     * @return array
     */
    private function getWebData()
    {
        $id = $this->request->get('id');
        if ($id) {
            $game = $this->gameQueryFactory->create()
                ->findOneById($id);
        } else {
            $game = new Game();
        }
        $title = 'Game '.$game->getId().': '.$game->getDate('Y-m-d');
        $label = 'Score';
        $columns = [];
        $columns[] = [
            'label' => '',
            'icon_class' => ''
        ]; //one empty column
        $categories = $game->getGameCategories();
        foreach ($categories as $category) {
            /** @var GameCategory $category */
            $columns[] = [
                'label' => $category->getCategory()->getName(),
                'icon_class' => $category->getCategory()->getIconClass()
            ];
        }
        //get game scores
        $columns[] = [
            'label' => 'Total',
            'icon_class' => 'fa fa-plus'
        ];
        $columns[] = [
            'label' => 'Rank',
            'icon_class' => 'fa fa-signal'
        ];
        $rows = [];
        $scores = $this->getGameScores($game);
        foreach ($game->getGamePlayers() as $gamePlayer) {
            $row = [];
            $playerNameColumnData = [
                $gamePlayer->getPlayer()->getName(),
            ];
            if ($gamePlayer->getWonderId()) {
                $playerNameColumnData[] = $gamePlayer->getWonder()->getName();
            }
            if ($gamePlayer->getSide()) {
                $playerNameColumnData[] = $gamePlayer->getSide();
            }
            $row[] = implode(' - ', $playerNameColumnData);
            foreach ($categories as $category) {
                /** @var GameCategory $category */
                $row[] = $scores[$gamePlayer->getPlayerId()][$category->getCategoryId()];
            }
            //add total
            $row[] = $gamePlayer->getPoints();
            $row[] = $gamePlayer->getPlace();
            $rows[] = $row;
        }
        return [
            'title' => $title,
            'label' => $label,
            'columns' => $columns,
            'rows' => $rows
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function execute()
    {
        if (!$this->apiMode) {
            return $this->getWebData();
        }
        throw new \Exception('Not Implemented Yet');
    }

    private function getGameScores(Game $game)
    {
        $scoresByPlayer = [];
        $scores = $game->getScores();
        foreach ($scores as $score) {
            /** @var Score $score */
            $scoresByPlayer[$score->getPlayerId()][$score->getCategoryId()] = $score->getValue();
        }
        return $scoresByPlayer;
    }
}
