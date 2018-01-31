<?php
namespace Model\Factory;

use Model\Factory;
use Wonders\Category;

class CategoryFactory
{
    /**
     * @var Factory
     */
    private $factory;
    /**
     * CategoryFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @return Category
     */
    public function create()
    {
        return $this->factory->create(Category::class);
    }
}
