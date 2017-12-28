<?php
namespace Controller\Category;

use Controller\AuthInterface;
use Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Wonders\Category;
use Wonders\CategoryQuery;

class SaveCategory extends BaseController implements AuthInterface
{
    /**
     * @return RedirectResponse
     */
    public function execute()
    {
        $id = $this->request->get('id');
        try {
            if ($id) {
                $category = CategoryQuery::create()
                    ->findOneById($id);
            } else {
                $category = new Category();
            }
            $category->setName($this->request->get('name'));
            $category->setSortOrder($this->request->get('sort_order'));
            $category->setOptional($this->request->get('optional'));
            $category->setIconClass($this->request->get('icon_class'));
            $category->save();
            $this->addFlashMessage(self::FLASH_MESSAGE_SUCCESS, 'The score category was saved');
            return new RedirectResponse($this->request->getBaseUrl().'/category/list');
        } catch (\Exception $e) {
            $this->addFlashMessage(self::FLASH_MESSAGE_ERROR, $e->getMessage());
            if ($id) {
                return new RedirectResponse($this->request->getBaseUrl() . '/category/edit?id=' . $id);
            }
            return new RedirectResponse($this->request->getBaseUrl() . '/category/edit');
        }
    }
}
