<?php
namespace Service;

use Model\Query\CategoryQueryFactory;
use Propel\Runtime\Collection\ObjectCollection;

class Category
{
    /**
     * @var CategoryQueryFactory
     */
    private $categoryQueryFactory;
    /**
     * @var array
     */
    private $cache = [];

    /**
     * Category constructor.
     * @param CategoryQueryFactory $categoryQueryFactory
     */
    public function __construct(
        CategoryQueryFactory $categoryQueryFactory
    ) {
        $this->categoryQueryFactory = $categoryQueryFactory;
    }

    /**
     * @param array $filter
     * @return ObjectCollection | \Wonders\Category[]
     */
    public function getCategories($filter = [])
    {
        $query = $this->categoryQueryFactory->create();
        if ($filter) {
            $query->filterByArray($filter);
        }
        $categories = $query->find();
        return $categories;
    }

    /**
     * @param $id
     * @return \Wonders\Category | null
     */
    public function getCategory($id)
    {
        if (!isset($this->cache[$id])) {
            $this->cache[$id] = $this->categoryQueryFactory->create()->findOneById($id);
        }
        return $this->cache[$id];
    }

    /**
     * @param \Wonders\Category $category
     * @return int
     */
    public function save(\Wonders\Category $category)
    {
        return $category->save();
    }
}
