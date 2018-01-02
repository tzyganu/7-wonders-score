<?php
namespace Controller\Category;

use Controller\AuthInterface;
use Controller\OutputController;
use Wonders\Category;
use Wonders\CategoryQuery;

class EditCategory extends OutputController implements AuthInterface
{
    /**
     * @var string
     */
    protected $selectedMenu = ['categories', 'categories-edit'];
    /**
     * @var string
     */
    protected $template = 'category/edit.html.twig';

    /**
     * @return string
     */
    public function execute()
    {
        $id = $this->request->get('id');
        if ($id) {
            $category = CategoryQuery::create()
                ->findOneById($id);
            if ($category) {
                return $this->render(['category' => $category]);
            }
        }
        return $this->render(['category' => new Category()]);
    }
}
