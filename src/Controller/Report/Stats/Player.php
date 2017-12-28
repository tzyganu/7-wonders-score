<?php
namespace Controller\Report\Stats;

use Wonders\GamePlayer;
use Wonders\PlayerQuery;

class Player extends Stats
{
    /**
     * @return array
     */
    protected function initRows()
    {
        $players = PlayerQuery::create()->find();
        $rows = [];
        foreach ($players as $player) {
            $rows[$player->getId()] = [
                'name' => $player->getName(),
                'played' => 0,
                'won' => 0,
                'percentage' => 0,
                'total_points' => 0,
                'average' => 0,
                'max' => null,
                'min' => null,
            ];
        }
        return $rows;
    }

    /**
     * @param GamePlayer $gamePlayer
     * @return string
     */
    protected function getRowKey(GamePlayer $gamePlayer)
    {
        return (string)($gamePlayer->getPlayerId());
    }

    /**
     * @return string
     */
    protected function getGridTitle()
    {
        return 'Player Stats';
    }
}
