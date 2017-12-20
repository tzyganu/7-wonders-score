<?php
namespace Controller\Player;

use Controller\AuthInterface;
use Propel\Runtime\Map\TableMap;
use Wonders\Player;

class EditPlayer extends PlayerController implements AuthInterface
{
    /**
     * @return array|Player
     */
    public function execute()
    {
        $id = $this->request->get('id');
        if ($id) {
            $player = $this->playerQueryFactory->create()
                ->findOneById($id);
            if ($player) {
                return ['player' => $player->toArray(TableMap::TYPE_FIELDNAME)];
            }
        }
        return ['player' => []];
    }
}
