<?php
namespace Controller\Category;

use Controller\AuthInterface;
use Controller\ControllerInterface;
use Model\Factory\CategoryFactory;
use Model\FlashMessage;
use Model\ResponseFactory;
use Model\UrlBuilder;
use Symfony\Component\HttpFoundation\Request;

class SaveCategory implements AuthInterface, ControllerInterface
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;
    /**
     * @var \Service\Category
     */
    private $categoryService;
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var UrlBuilder
     */
    private $urlBuilder;
    /**
     * @var FlashMessage
     */
    private $flashMessage;

    /**
     * SaveCategory constructor.
     * @param Request $request
     * @param ResponseFactory $responseFactory
     * @param \Service\Category $categoryService
     * @param CategoryFactory $categoryFactory
     * @param UrlBuilder $urlBuilder
     * @param FlashMessage $flashMessage
     */
    public function __construct(
        Request $request,
        ResponseFactory $responseFactory,
        \Service\Category $categoryService,
        CategoryFactory $categoryFactory,
        UrlBuilder $urlBuilder,
        FlashMessage $flashMessage
    ) {
        $this->request          = $request;
        $this->responseFactory  = $responseFactory;
        $this->categoryService  = $categoryService;
        $this->categoryFactory  = $categoryFactory;
        $this->urlBuilder       = $urlBuilder;
        $this->flashMessage     = $flashMessage;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function execute()
    {
        $id = $this->request->get('id');
        try {
            if ($id) {
                $category = $this->categoryService->getCategory($id);
                if (!$category) {
                    throw new \Exception("Category with id {$id} does not exist");
                }
            } else {
                $category = $this->categoryFactory->create();
            }
            $category->setName($this->request->get('name'));
            $category->setSortOrder($this->request->get('sort_order'));
            $category->setOptional($this->request->get('optional'));
            $category->setIconClass($this->request->get('icon_class'));
            $category->setColor($this->request->get('color'));
            $this->categoryService->save($category);
            $this->flashMessage->addSuccessMessage("The score category was saved");
            return $this->responseFactory->create(
                ResponseFactory::REDIRECT,
                [
                    'url' => $this->urlBuilder->getUrl("category/list")
                ]
            );
        } catch (\Exception $e) {
            $this->flashMessage->addErrorMessage($e->getMessage());
            $url = ($id) ? 'category/edit' : 'category/new';
            $params = ($id) ? ['id' => $id] : [];
            return $this->responseFactory->create(
                ResponseFactory::REDIRECT,
                [
                    'url' => $this->urlBuilder->getUrl($url, $params)
                ]
            );
        }
    }
}
