<?php
namespace Service;

use Model\Query\WonderGroupQueryFactory;
use Model\Query\WonderGroupWonderQueryFactory;
use Propel\Runtime\Collection\ObjectCollection;

class WonderGroupWonder
{
    /**
     * @var WonderGroupQueryFactory
     */
    private $wonderGroupWonderQueryFactory;
    /**
     * @var array
     */
    private $cache = [];

    /**
     * WonderGroupWonder constructor.
     * @param WonderGroupWonderQueryFactory $wonderGroupWonderQueryFactory
     */
    public function __construct(
        WonderGroupWonderQueryFactory $wonderGroupWonderQueryFactory
    ) {
        $this->wonderGroupWonderQueryFactory = $wonderGroupWonderQueryFactory;
    }

    /**
     * @param array $filter
     * @return ObjectCollection | \Wonders\WonderGroup[]
     */
    public function getWonderGroupWonders($filter = [])
    {
        $query = $this->wonderGroupWonderQueryFactory->create();
        if ($filter) {
            $query->filterByArray($filter);
        }
        $categories = $query->find();
        return $categories;
    }

    /**
     * @param $id
     * @return \Wonders\WonderGroup | null
     */
    public function getWonderGroup($id)
    {
        if (!isset($this->cache[$id])) {
            $this->cache[$id] = $this->wonderGroupWonderQueryFactory->create()->findOneById($id);
        }
        return $this->cache[$id];
    }

    /**
     * @param \Wonders\WonderGroupWonder $wonderGroupWonder
     * @return int
     */
    public function save(\Wonders\WonderGroupWonder $wonderGroupWonder)
    {
        return $wonderGroupWonder->save();
    }

    /**
     * @param \Wonders\WonderGroupWonder $wonderGroupWonder
     * @return void
     */
    public function delete(\Wonders\WonderGroupWonder $wonderGroupWonder)
    {
        $wonderGroupWonder->delete();
    }
}
