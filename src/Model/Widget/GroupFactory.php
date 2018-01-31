<?php
namespace Model\Widget;

use Model\Factory;

class GroupFactory
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * GroupFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param array $data
     * @return Group
     */
    public function create(array $data = [])
    {
        return $this->factory->create(Group::class, $data);
    }
}
