<?php
namespace Model\Grid;

use Model\Grid;

class Factory
{
    /**
     * @var \Model\Factory
     */
    private $factory;
    /**
     * Factory constructor.
     * @param \Model\Factory $factory
     */
    public function __construct(\Model\Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array $data
     * @return Grid
     */
    public function create(array $data = [])
    {
        return $this->factory->create(Grid::class, ['options' => $data]);
    }
}
