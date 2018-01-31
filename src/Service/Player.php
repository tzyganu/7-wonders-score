<?php
namespace Service;

use Model\Query\PlayerQueryFactory;

class Player
{
    /**
     * @var PlayerQueryFactory
     */
    private $playerQueryFactory;
    /**
     * @var array
     */
    private $cache = [];

    /**
     * Player constructor.
     * @param PlayerQueryFactory $playerQueryFactory
     */
    public function __construct(
        PlayerQueryFactory $playerQueryFactory
    ) {
        $this->playerQueryFactory = $playerQueryFactory;
    }

    /**
     * @param array $filter
     * @return mixed
     */
    public function getPlayers($filter = [])
    {
        $query = $this->playerQueryFactory->create();
        if (count($filter)) {
            $query->filterByArray($filter);
        }
        return $query->find();
    }

    /**
     * @param $id
     * @return \Wonders\Player
     */
    public function getPlayer($id)
    {
        if (!isset($this->cache[$id])) {
            $query = $this->playerQueryFactory->create();
            $this->cache[$id] = $query->findOneById($id);
        }
        return $this->cache[$id];
    }

    /**
     * @param \Wonders\Player $player
     * @return int
     */
    public function save(\Wonders\Player $player)
    {
        return $player->save();
    }
}
