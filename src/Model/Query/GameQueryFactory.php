<?php
namespace Model\Query;

use Model\Factory;
use Wonders\GameQuery;

class GameQueryFactory
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * GameQueryFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param array $data
     * @return GameQuery
     */
    public function create(array $data = [])
    {
        return $this->factory->create(GameQuery::class, $data);
    }
}
