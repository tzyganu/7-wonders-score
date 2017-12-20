<?php
namespace Controller\Wonder;

use Controller\AuthInterface;
use Propel\Runtime\Map\TableMap;
use Wonders\Wonder;

class SaveWonder extends WonderController implements AuthInterface
{
    /**
     * @return array
     */
    public function execute()
    {
        $id = $this->request->get('id');
        if ($id) {
            $category = $this->wonderQueryFactory->create()
                ->findOneById($id);
        } else {
            $category = new Wonder();
        }
        $category->setName($this->request->get('name'));
        try {
            $category->save();
        } catch (\Exception $e) {
            echo $e->getMessage();exit;
        }
        return $category->toArray(TableMap::TYPE_FIELDNAME);
    }
}
