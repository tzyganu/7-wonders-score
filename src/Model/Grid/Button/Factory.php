<?php
namespace Model\Grid\Button;

use Model\Grid\Button;

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
     * @return Button
     */
    public function create(array $data = [])
    {
        return $this->factory->create(
            Button::class,
            $data
        );
    }
}
