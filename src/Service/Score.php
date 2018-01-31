<?php
namespace Service;

use Model\Query\ScoreQueryFactory;
use Propel\Runtime\ActiveQuery\Criteria;
use Wonders\ScoreQuery;

class Score
{
    /**
     * @var ScoreQueryFactory
     */
    private $scoreQueryFactory;
    /**
     * @var array
     */
    private $cache = [];

    /**
     * Score constructor.
     * @param ScoreQueryFactory $scoreQueryFactory
     */
    public function __construct(
        ScoreQueryFactory $scoreQueryFactory
    ) {
        $this->scoreQueryFactory = $scoreQueryFactory;
    }

    /**
     * @param array $filter
     * @return mixed
     */
    public function getScores($filter = [])
    {
        $gamesQuery = $this->scoreQueryFactory->create();
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
     * @return \Wonders\Score
     */
    public function getScore($id)
    {
        if (!isset($this->cache[$id])) {
            $this->cache[$id] = $this->scoreQueryFactory->create()->findOneById($id);
        }
        return $this->cache[$id];
    }

    /**
     * @param ScoreQuery $scoreQuery
     * @param $filters
     * @return ScoreQuery
     */
    private function applyGameFilters(ScoreQuery $scoreQuery, $filters)
    {
        if (isset($filters['date']['start']) && !empty($filters['date']['start'])) {
            $scoreQuery->useGameQuery()
                ->filterByDate($filters['date']['start'], Criteria::GREATER_EQUAL)
                ->endUse();
        }
        if (isset($filters['date']['end']) && !empty($filters['date']['end'])) {
            $scoreQuery->useGameQuery()
                ->filterByDate($filters['date']['end'], Criteria::LESS_EQUAL)
                ->endUse();
        }
        if (isset($filters['player_count']) && !empty($filters['player_count'])) {
            $scoreQuery->useGameQuery()
                ->filterByArray(['PlayerCount' => $filters['player_count']])
                ->endUse();
        }
        return $scoreQuery;
    }

    /**
     * @param \Wonders\Score $score
     * @return int
     */
    public function save(\Wonders\Score $score)
    {
        return $score->save();
    }
}
