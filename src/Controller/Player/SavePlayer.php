<?php
namespace Controller\Player;

use Controller\AuthInterface;
use Propel\Runtime\Map\TableMap;
use Wonders\Player;

class SavePlayer extends PlayerController implements AuthInterface
{
    /**
     * @return array
     */
    public function execute()
    {
        $id = $this->request->get('id');
        if ($id) {
            $player = $this->playerQueryFactory->create()
                ->findOneById($id);
        } else {
            $player = new Player();
        }
        $player->setName($this->request->get('name'));
        $player->save();
        return $player->toArray(TableMap::TYPE_FIELDNAME);
    }
}
