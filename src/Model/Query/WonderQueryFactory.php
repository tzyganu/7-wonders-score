<?php
namespace Model\Query;

use Model\Factory;
use Wonders\WonderQuery;

class WonderQueryFactory
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * WonderQueryFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param array $data
     * @return WonderQuery
     */
    public function create(array $data = [])
    {
        return $this->factory->create(WonderQuery::class, $data);
    }
}
