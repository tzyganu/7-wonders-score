<?php
namespace Controller\Category;

use Controller\AuthInterface;
use Propel\Runtime\Map\TableMap;

class EditCategory extends CategoryController implements AuthInterface
{
    public function execute()
    {
        $id = $this->request->get('id');
        if ($id) {
            $category = $this->categoryQueryFactory->create()
                ->findOneById($id);
            if ($category) {
                return ['category' => $category->toArray(TableMap::TYPE_FIELDNAME)];
            }
        }
        return ['category' => []];
    }
}
