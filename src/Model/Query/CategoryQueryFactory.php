<?php
namespace Model\Query;

use Model\Factory;
use Wonders\CategoryQuery;

class CategoryQueryFactory
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * CategoryQueryFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param array $data
     * @return CategoryQuery
     */
    public function create(array $data = [])
    {
        return $this->factory->create(CategoryQuery::class, $data);
    }
}
