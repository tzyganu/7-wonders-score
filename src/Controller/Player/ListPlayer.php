<?php
namespace Controller\Player;

use Propel\Runtime\Map\TableMap;

class ListPlayer extends PlayerController
{

    public function execute()
    {
        return [
            'players' => $this->playerQueryFactory->create()
                ->orderByName()
                ->find()
                ->toArray(null, false, TableMap::TYPE_FIELDNAME)
        ];
    }
}
