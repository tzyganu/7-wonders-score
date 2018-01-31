<?php
namespace Model\Factory;

use Model\Factory;
use Wonders\Wonder;

class WonderFactory
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * WonderFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param $data
     * @return Wonder
     */
    public function create(array $data = [])
    {
        return $this->factory->create(Wonder::class, $data);
    }
}
