<?php
namespace Model\Factory;

use Model\Factory;
use Wonders\GameCategory;

class GameCategoryFactory
{
    /**
     * @var Factory
     */
    private $factory;
    /**
     * GameCategory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @return GameCategory
     */
    public function create()
    {
        return $this->factory->create(GameCategory::class);
    }
}
