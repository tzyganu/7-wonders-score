<?php
namespace Model\Query;

use Model\Factory;
use Wonders\GamePlayerQuery;

class GamePlayerQueryFactory
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * GamePlayerQueryFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param array $data
     * @return GamePlayerQuery
     */
    public function create(array $data = [])
    {
        return $this->factory->create(GamePlayerQuery::class, $data);
    }
}
