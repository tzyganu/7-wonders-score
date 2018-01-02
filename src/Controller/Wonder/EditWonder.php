<?php
namespace Controller\Wonder;

use Controller\AuthInterface;
use Controller\OutputController;
use Wonders\Wonder;
use Wonders\WonderQuery;

class EditWonder extends OutputController implements AuthInterface
{
    /**
     * @var string
     */
    protected $selectedMenu = ['wonders', 'wonders-edit'];
    /**
     * @var string
     */
    protected $template = 'wonder/edit.html.twig';

    /**
     * @return string
     */
    public function execute()
    {
        $id = $this->request->get('id');
        if ($id) {
            $wonder = WonderQuery::create()
                ->findOneById($id);
            if ($wonder) {
                return $this->render(['wonder' => $wonder]);
            }
        }
        return $this->render(['wonder' => new Wonder()]);
    }
}
