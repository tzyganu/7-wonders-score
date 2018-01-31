<?php
namespace Service;

use Model\Query\WonderGroupQueryFactory;
use Propel\Runtime\Collection\ObjectCollection;

class WonderGroup
{
    /**
     * @var WonderGroupQueryFactory
     */
    private $wonderGroupQueryFactory;
    /**
     * @var array
     */
    private $cache = [];

    /**
     * WonderGroup constructor.
     * @param WonderGroupQueryFactory $wonderGroupQueryFactory
     */
    public function __construct(
        WonderGroupQueryFactory $wonderGroupQueryFactory
    ) {
        $this->wonderGroupQueryFactory = $wonderGroupQueryFactory;
    }

    /**
     * @param array $filter
     * @return ObjectCollection | \Wonders\WonderGroup[]
     */
    public function getWonderGroups($filter = [])
    {
        $query = $this->wonderGroupQueryFactory->create();
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
            $this->cache[$id] = $this->wonderGroupQueryFactory->create()->findOneById($id);
        }
        return $this->cache[$id];
    }

    /**
     * @param \Wonders\WonderGroup $wonderGroup
     * @return int
     */
    public function save(\Wonders\WonderGroup $wonderGroup)
    {
        return $wonderGroup->save();
    }
}
