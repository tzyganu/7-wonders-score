<?php
namespace Controller\Category;

use Controller\ControllerInterface;
use Controller\GridController;
use Model\Grid;
use Service\Category;
use Symfony\Component\HttpFoundation\Request;

class ListCategory extends GridController implements ControllerInterface
{
    const GRID_NAME = 'category';
    /**
     * @var Category
     */
    private $categoryService;

    /**
     * ListCategory constructor.
     * @param Request $request
     * @param Grid\Loader $gridLoader
     * @param \Twig_Environment $twig
     * @param Category $categoryService
     * @param string $template
     * @param string $pageTitle
     * @param array $selectedMenu
     */
    public function __construct(
        Request $request,
        Grid\Loader $gridLoader,
        \Twig_Environment $twig,
        Category $categoryService,
        $template = '',
        $pageTitle = '',
        array $selectedMenu = []
    ) {
        $this->categoryService = $categoryService;
        parent::__construct($request, $gridLoader, $twig, $template, $pageTitle, $selectedMenu);
    }

    /**
     * @return mixed
     */
    protected function getRows()
    {
        return $this->categoryService->getCategories();
    }
}
