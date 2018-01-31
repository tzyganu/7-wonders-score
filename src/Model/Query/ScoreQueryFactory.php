<?php
namespace Model\Query;

use Model\Factory;
use Wonders\ScoreQuery;

class ScoreQueryFactory
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * ScoreQueryFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param array $data
     * @return ScoreQuery
     */
    public function create(array $data = [])
    {
        return $this->factory->create(ScoreQuery::class, $data);
    }
}
