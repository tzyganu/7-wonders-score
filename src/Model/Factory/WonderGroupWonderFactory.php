<?php
namespace Model\Factory;

use Model\Factory;
use Wonders\WonderGroupWonder;

class WonderGroupWonderFactory
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
     * @return WonderGroupWonder
     */
    public function create()
    {
        return $this->factory->create(WonderGroupWonder::class);
    }
}
