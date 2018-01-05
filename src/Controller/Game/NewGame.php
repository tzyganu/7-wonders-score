<?php

namespace Controller\Game;

use Controller\AuthInterface;
use Controller\OutputController;
use Model\Side;
use Propel\Runtime\Map\TableMap;
use Wonders\Category;
use Wonders\CategoryQuery;
use Wonders\Game;
use Wonders\PlayerQuery;
use Wonders\WonderQuery;

class NewGame extends OutputController implements AuthInterface
{
    protected $template = 'game/new.html.twig';

    protected $selectedMenu = ['games', 'games-new'];

    /**
     * @return string
     */
    public function execute()
    {
        return $this->render([
            'categories' => CategoryQuery::create()->orderBySortOrder()->find()->toArray(null, false, TableMap::TYPE_FIELDNAME),
            'existing_players' => $this->getExistingPlayers(),
            'wonders' => $this->getWonders(),
            'sides' => $this->getSides(),
            'game_date' => date('Y-m-d'),
            'min_players' => Game::MIN_PLAYERS,
            'max_players' => Game::MAX_PLAYERS,
            'default_players' => Game::DEFAULT_PLAYERS
        ]);
    }
    /**
     * @return array
     */
    private function getExistingPlayers()
    {
        $players = PlayerQuery::create()
            ->orderByName()
            ->find();
        $playerArr = [];
        foreach ($players as $player) {
            $playerArr[] = [
                'name' => $player->getName(),
                'id' => $player->getId()
            ];
        }
        return $playerArr;
    }

    /**
     * @return array
     */
    private function getWonders()
    {
        $wonders = WonderQuery::create()
            ->orderByName()
            ->find();
        $wondersArr = [];
        foreach ($wonders as $wonder) {
            $wondersArr[] = [
                'name' => $wonder->getName(),
                'id' => $wonder->getId()
            ];
        }
        return $wondersArr;
    }

    /**
     * @return array
     */
    private function getSides()
    {
        $sideModel = new Side();
        return $sideModel->getSides();
    }
}
