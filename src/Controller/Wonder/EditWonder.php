<?php
namespace Controller\Wonder;

use Controller\AuthInterface;
use Propel\Runtime\Map\TableMap;

class EditWonder extends WonderController implements AuthInterface
{
    /**
     * @return mixed
     */
    public function execute()
    {
        $id = $this->request->get('id');
        if ($id) {
            $wonder = $this->wonderQueryFactory->create()
                ->findOneById($id);
            if ($wonder) {
                return ['wonder' => $wonder->toArray(TableMap::TYPE_FIELDNAME)];
            }
        }
        return ['wonder' => []];
    }
}
