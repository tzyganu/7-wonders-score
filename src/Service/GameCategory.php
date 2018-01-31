<?php
namespace Service;

use Model\Query\GameCategoryQueryFactory;

class GameCategory
{
    /**
     * @var GameCAtegoryQueryFactory
     */
    private $gameCategoryQueryFactory;
    /**
     * @var array
     */
    private $cache = [];

    /**
     * GameCategory constructor.
     * @param GameCategoryQueryFactory $gameCategoryQueryFactory
     */
    public function __construct(
        GameCategoryQueryFactory $gameCategoryQueryFactory
    ) {
        $this->gameCategoryQueryFactory = $gameCategoryQueryFactory;
    }

    /**
     * @param array $filter
     * @return mixed
     */
    public function getGameCategories($filter = [])
    {
        $query = $this->gameCategoryQueryFactory->create();
        if (count($filter)) {
            $query->filterByArray($filter);
        }
        return $query->find();

    }

    /**
     * @param $id
     * @return \Wonders\GameCategory
     */
    public function getGameCategory($id)
    {
        if (!isset($this->cache[$id])) {
            $this->cache[$id] = $this->gameCategoryQueryFactory->create()->findOneById($id);
        }
        return $this->cache[$id];
    }

    /**
     * @param \Wonders\GameCategory $gameCategory
     * @return int
     */
    public function save(\Wonders\GameCategory $gameCategory)
    {
        return $gameCategory->save();
    }
}
