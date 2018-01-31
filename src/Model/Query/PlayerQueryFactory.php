<?php
namespace Model\Query;

use Model\Factory;
use Wonders\PlayerQuery;

class PlayerQueryFactory
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * PlayerQueryFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param array $data
     * @return PlayerQuery
     */
    public function create(array $data = [])
    {
        return $this->factory->create(PlayerQuery::class, $data);
    }
}
