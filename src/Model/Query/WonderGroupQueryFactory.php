<?php
namespace Model\Query;

use Model\Factory;
use Wonders\WonderGroupQuery;

class WonderGroupQueryFactory
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * WonderGroupQueryFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param array $data
     * @return WonderGroupQuery
     */
    public function create(array $data = [])
    {
        return $this->factory->create(WonderGroupQuery::class, $data);
    }
}
