<?php
namespace Model\Factory;

use Model\Factory;
use Wonders\WonderGroup;

class WonderGroupFactory
{
    /**
     * @var Factory
     */
    private $factory;
    /**
     * WonderGroupFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @return WonderGroup
     */
    public function create()
    {
        return $this->factory->create(WonderGroup::class);
    }
}
