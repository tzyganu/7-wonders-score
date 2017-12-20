<?php
namespace Controller\Game;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\TableMap;

class ListGame extends GameController
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $games = $this->gameQueryFactory->create()
            ->addAsColumn('id', 'Game.id')
            ->addAsColumn('date', 'Game.date')
            ->useGamePlayerQuery()
                ->usePlayerQuery()
                    ->addAsColumn('player_name', 'GROUP_CONCAT(name SEPARATOR ", ")')
                ->endUse()
                ->groupByGameId()
            ->endUse()
            ->orderByDate(Criteria::DESC)
            ->find()->toArray(null, false, TableMap::TYPE_FIELDNAME);
        return ['games' => $games];
    }
}
