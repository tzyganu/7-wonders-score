<?php
namespace Controller\Category;

use Controller\AuthInterface;
use Propel\Runtime\Map\TableMap;
use Wonders\Category;

class SaveCategory extends CategoryController implements AuthInterface
{
    public function execute()
    {
        $id = $this->request->get('id');
        if ($id) {
            $category = $this->categoryQueryFactory->create()
                ->findOneById($id);
        } else {
            $category = new Category();
        }
        $category->setName($this->request->get('name'));
        $category->setSortOrder($this->request->get('sort_order'));
        $category->setOptional($this->request->get('optional'));
        $category->setIconClass($this->request->get('icon_class'));
        $category->save();
        return $category->toArray(TableMap::TYPE_FIELDNAME);
    }
}
