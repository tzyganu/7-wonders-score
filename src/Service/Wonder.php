<?php
namespace Service;

use Model\Query\WonderQueryFactory;

class Wonder
{
    /**
     * @var WonderQueryFactory
     */
    private $wonderQueryFactory;
    /**
     * @var array
     */
    private $cache = [];

    /**
     * Wonder constructor.
     * @param WonderQueryFactory $wonderQueryFactory
     */
    public function __construct(
        WonderQueryFactory $wonderQueryFactory
    ) {
        $this->wonderQueryFactory = $wonderQueryFactory;
    }

    /**
     * @param array $filter
     * @return \Propel\Runtime\Collection\ObjectCollection|\Wonders\Wonder[]
     */
    public function getWonders($filter = [])
    {
        $query = $this->wonderQueryFactory->create();
        if ($filter) {
            $query->filterByArray($filter);
        }
        return $query->find();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getWonder($id)
    {
        if (!isset($this->cache[$id])) {
            $query = $this->wonderQueryFactory->create();
            $this->cache[$id] = $query->findOneById($id);
        }
        return $this->cache[$id];
    }

    /**
     * @param \Wonders\Wonder $wonder
     * @return int
     */
    public function save(\Wonders\Wonder $wonder)
    {
        return $wonder->save();
    }
}
