<?php
namespace Service;

use Model\Query\GameQueryFactory;

class Game
{
    /**
     * @var GameQueryFactory
     */
    private $gameQueryFactory;
    /**
     * @var array
     */
    private $cache = [];

    /**
     * Game constructor.
     * @param GameQueryFactory $gameQueryFactory
     */
    public function __construct(
        GameQueryFactory $gameQueryFactory
    ) {
        $this->gameQueryFactory = $gameQueryFactory;
    }

    /**
     * @param array $filter
     * @return mixed
     */
    public function getGames($filter = [])
    {
        $gamesQuery = $this->gameQueryFactory->create();
        if (count($filter)) {
            $gamesQuery->filterByArray($filter);
        }
        return $gamesQuery->find();

    }

    /**
     * @param $id
     * @return \Wonders\Game
     */
    public function getGame($id)
    {
        if (!isset($this->cache[$id])) {
            $this->cache[$id] = $this->gameQueryFactory->create()->findOneById($id);
        }
        return $this->cache[$id];
    }

    /**
     * @param \Wonders\Game $game
     * @return int
     */
    public function save(\Wonders\Game $game)
    {
        return $game->save();
    }
}
