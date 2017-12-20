<?php
namespace Controller\Category;

use Propel\Runtime\Map\TableMap;

class ListCategory extends CategoryController
{
    /**
     * @return mixed
     */
    public function execute()
    {
        return [
            'categories' => $this->categoryQueryFactory->create()
                ->orderBySortOrder()
                ->find()
                ->toArray(null, false, TableMap::TYPE_FIELDNAME)
        ];
    }
}
