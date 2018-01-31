<?php
namespace Controller\Category;

use Controller\AuthInterface;
use Controller\ControllerInterface;
use Model\Factory\CategoryFactory;
use Model\FlashMessage;
use Model\ResponseFactory;
use Model\UrlBuilder;
use Service\Category;
use Symfony\Component\HttpFoundation\Request;

class EditCategory implements AuthInterface, ControllerInterface
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Category
     */
    private $categoryService;
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var FlashMessage
     */
    private $flashMessage;

    private $urlBuilder;
    /**
     * @var array
     */
    private $selectedMenu;
    /**
     * @var string
     */
    private $template;

    /**
     * EditCategory constructor.
     * @param Request $request
     * @param CategoryFactory $categoryFactory
     * @param \Twig_Environment $twig
     * @param Category $categoryService
     * @param ResponseFactory $responseFactory
     * @param FlashMessage $flashMessage
     * @param UrlBuilder $urlBuilder
     * @param string $template
     * @param array $selectedMenu
     */
    public function __construct(
        Request $request,
        CategoryFactory $categoryFactory,
        \Twig_Environment $twig,
        Category $categoryService,
        ResponseFactory $responseFactory,
        FlashMessage $flashMessage,
        UrlBuilder $urlBuilder,
        $template = '',
        $selectedMenu = []
    ) {
        $this->request          = $request;
        $this->categoryFactory  = $categoryFactory;
        $this->twig             = $twig;
        $this->categoryService  = $categoryService;
        $this->responseFactory  = $responseFactory;
        $this->flashMessage     = $flashMessage;
        $this->urlBuilder       = $urlBuilder;
        $this->template         = $template;
        $this->selectedMenu     = $selectedMenu;
    }

    /**
     * @return string|\Symfony\Component\HttpFoundation\Response
     */
    public function execute()
    {
        $id = $this->request->get('id');
        if ($id) {
            $category = $this->categoryService->getCategory($id);
            if (!$category) {
                $this->flashMessage->addErrorMessage("The score category does not exit");
                return $this->responseFactory->create(
                    ResponseFactory::REDIRECT,
                    [
                        'url' => $this->urlBuilder->getUrl('/category/list')
                    ]
                );
            }
        } else {
            $category = $this->categoryFactory->create();
        }
        return $this->twig->render(
            $this->template,
            [
                'category' => $category,
                'selectedMenu' => $this->selectedMenu,
                'page_title' => $this->getPageTitle($category)
            ]
        );
    }

    /**
     * @param \Wonders\Category $category
     * @return string
     */
    private function getPageTitle(\Wonders\Category $category)
    {
        if ($category->getId()) {
            return "Edit Score Category: ".$category->getName();
        }
        return "New Category";
    }
}
