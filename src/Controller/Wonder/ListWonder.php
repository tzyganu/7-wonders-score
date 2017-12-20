<?php
namespace Controller\Wonder;

use Propel\Runtime\Map\TableMap;

class ListWonder extends WonderController
{
    /**
     * @return mixed
     */
    public function execute()
    {
        return [
            'wonders' => $this->wonderQueryFactory->create()
                ->orderByName()
                ->find()
                ->toArray(null, false, TableMap::TYPE_FIELDNAME)
        ];
    }
}
