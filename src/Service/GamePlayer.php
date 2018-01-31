<?php
namespace Service;

use Model\Query\GamePlayerQueryFactory;
use Propel\Runtime\ActiveQuery\Criteria;
use Wonders\GamePlayerQuery;

class GamePlayer
{
    /**
     * @var GamePlayerQueryFactory
     */
    private $gamePlayerQueryFactory;
    /**
     * @var array
     */
    private $cache = [];

    /**
     * GamePlayer constructor.
     * @param GamePlayerQueryFactory $gamePlayerQueryFactory
     */
    public function __construct(
        GamePlayerQueryFactory $gamePlayerQueryFactory
    ) {
        $this->gamePlayerQueryFactory = $gamePlayerQueryFactory;
    }

    /**
     * @param array $filter
     * @return mixed
     */
    public function getGamePlayers($filter = [])
    {
        $gamesQuery = $this->gamePlayerQueryFactory->create();
        if (isset($filter['_game'])) {
            $this->applyGameFilters($gamesQuery, $filter['_game']);
            unset($filter['_game']);
        }
        if (count($filter)) {
            $gamesQuery->filterByArray($filter);
        }
        return $gamesQuery->find();

    }

    /**
     * @param $id
     * @return \Wonders\GamePlayer
     */
    public function getGamePlayer($id)
    {
        if (!isset($this->cache[$id])) {
            $this->cache[$id] = $this->gamePlayerQueryFactory->create()->findOneById($id);
        }
        return $this->cache[$id];
    }

    /**
     * @param GamePlayerQuery $gamePlayerQuery
     * @param $filters
     * @return GamePlayerQuery
     */
    private function applyGameFilters(GamePlayerQuery $gamePlayerQuery, $filters)
    {
        if (isset($filters['date']['start']) && !empty($filters['date']['start'])) {
            $gamePlayerQuery->useGameQuery()
                ->filterByDate($filters['date']['start'], Criteria::GREATER_EQUAL)
                ->endUse();
        }
        if (isset($filters['date']['end']) && !empty($filters['date']['end'])) {
            $gamePlayerQuery->useGameQuery()
                ->filterByDate($filters['date']['end'], Criteria::LESS_EQUAL)
                ->endUse();
        }
        if (isset($filters['player_count']) && !empty($filters['player_count'])) {
            $gamePlayerQuery->useGameQuery()
                ->filterByArray(['PlayerCount' => $filters['player_count']])
                ->endUse();
        }
        return $gamePlayerQuery;
    }

    /**
     * @param \Wonders\GamePlayer $gamePlayer
     * @return int
     */
    public function save(\Wonders\GamePlayer $gamePlayer)
    {
        return $gamePlayer->save();
    }
}
