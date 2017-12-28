<?php
namespace Controller\Wonder;

use Controller\AuthInterface;
use Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Wonders\Wonder;
use Wonders\WonderQuery;

class SaveWonder extends BaseController implements AuthInterface
{
    /**
     * @return RedirectResponse
     */
    public function execute()
    {
        $id = $this->request->get('id');
        try {
            if ($id) {
                $wonder = WonderQuery::create()
                    ->findOneById($id);
            } else {
                $wonder = new Wonder();
            }
            $wonder->setName($this->request->get('name'));
            $wonder->save();
            $this->addFlashMessage(self::FLASH_MESSAGE_SUCCESS, 'The wonder was saved');
            return new RedirectResponse($this->request->getBaseUrl().'/wonder/list');
        } catch (\Exception $e) {
            $this->addFlashMessage(self::FLASH_MESSAGE_ERROR, $e->getMessage());
            if ($id) {
                return new RedirectResponse($this->request->getBaseUrl() . '/wonder/edit?id=' . $id);
            }
            return new RedirectResponse($this->request->getBaseUrl() . '/wonder/edit');
        }
    }
}
