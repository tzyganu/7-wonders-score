<?php
namespace Model\Query;

use Model\Factory;
use Wonders\GameCategoryQuery;

class GameCategoryQueryFactory
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * GameCategoryQueryFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param array $data
     * @return GameCategoryQuery
     */
    public function create(array $data = [])
    {
        return $this->factory->create(GameCategoryQuery::class, $data);
    }
}
