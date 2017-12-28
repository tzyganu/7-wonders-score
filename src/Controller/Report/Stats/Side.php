<?php
namespace Controller\Report\Stats;

use Factory\GamePlayerQuery;
use Model\Side as SideModel;
use Symfony\Component\HttpFoundation\Request;
use Wonders\GamePlayer;

class Side extends Stats
{
    /**
     * @return array
     */
    protected function initRows()
    {
        $rows = [];
        $sideModel = new SideModel();
        $sides = $sideModel->getSides();
        foreach ($sides as $side) {
            $rows[$side['id']] = [
                'name' => $side['name'],
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
     * @return bool
     */
    protected function validate(GamePlayer $gamePlayer)
    {
        return (!empty($gamePlayer->getSide()));
    }

    /**
     * @param GamePlayer $gamePlayer
     * @return string
     */
    protected function getRowKey(GamePlayer $gamePlayer)
    {
        return $gamePlayer->getSide();
    }

    /**
     * @return string
     */
    protected function getGridTitle()
    {
        return 'Side Stats';
    }
}
