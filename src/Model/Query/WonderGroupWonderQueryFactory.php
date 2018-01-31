<?php
namespace Model\Query;

use Model\Factory;
use Wonders\WonderGroupWonderQuery;

class WonderGroupWonderQueryFactory
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * WonderGroupWonderQueryFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param array $data
     * @return WonderGroupWonderQuery
     */
    public function create(array $data = [])
    {
        return $this->factory->create(WonderGroupWonderQuery::class, $data);
    }
}
