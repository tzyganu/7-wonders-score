<?php
namespace Controller\Report\Stats;

use Model\Side;
use Wonders\GamePlayer;
use Wonders\WonderQuery;

class Wonder extends Stats
{
    /**
     * @return array
     */
    protected function initRows()
    {
        $wonders = WonderQuery::create()->find();
        $rows = [];
        $sideModel = new Side();
        $sides = $sideModel->getSides();
        foreach ($wonders as $wonder) {
            foreach ($sides as $side) {
                $rows[$wonder->getId() . '_'.$side['id']] = [
                    'name' => $wonder->getName() . ' '. $side['name'],
                    'played' => 0,
                    'won' => 0,
                    'percentage' => 0,
                    'total_points' => 0,
                    'average' => 0,
                    'max' => null,
                    'min' => null,
                ];
            }
        }
        return $rows;
    }

    /**
     * @param GamePlayer $gamePlayer
     * @return bool
     */
    protected function validate(GamePlayer $gamePlayer)
    {
        return (!empty($gamePlayer->getSide()) && !empty($gamePlayer->getWonderId()));
    }

    /**
     * @param GamePlayer $gamePlayer
     * @return string
     */
    protected function getRowKey(GamePlayer $gamePlayer)
    {
        return $gamePlayer->getWonderId().'_'.$gamePlayer->getSide();
    }

    /**
     * @return string
     */
    protected function getGridTitle()
    {
        return 'Wonder Stats';
    }
}
