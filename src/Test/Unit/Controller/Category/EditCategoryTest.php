<?php
namespace Test\Unit\Controller\Category;

use Controller\Category\EditCategory;
use Model\Factory\CategoryFactory;
use Model\FlashMessage;
use Model\ResponseFactory;
use Model\UrlBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\Category;
use Symfony\Component\HttpFoundation\Request;

class EditCategoryTest extends TestCase
{
    /**
     * @var Request | MockObject
     */
    private $request;
    /**
     * @var CategoryFactory | MockObject
     */
    private $categoryFactory;
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
    /**
     * @var Category | MockObject
     */
    private $categoryService;
    /**
     * @var ResponseFactory | MockObject
     */
    private $responseFactory;
    /**
     * @var FlashMessage | MockObject
     */
    private $flashMessage;
    /**
     * @var UrlBuilder | MockObject
     */
    private $urlBuilder;
    /**
     * @var EditCategory
     */
    private $controller;

    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->request          = $this->createMock(Request::class);
        $this->categoryFactory  = $this->createMock(CategoryFactory::class);
        $this->twig             = $this->createMock(\Twig_Environment::class);
        $this->categoryService  = $this->createMock(Category::class);
        $this->responseFactory  = $this->createMock(ResponseFactory::class);
        $this->flashMessage     = $this->createMock(FlashMessage::class);
        $this->urlBuilder       = $this->createMock(UrlBuilder::class);
        $this->controller       = new EditCategory(
            $this->request,
            $this->categoryFactory,
            $this->twig,
            $this->categoryService,
            $this->responseFactory,
            $this->flashMessage,
            $this->urlBuilder
        );
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->request         = null;
        $this->categoryFactory = null;
        $this->categoryService = null;
        $this->responseFactory = null;
        $this->flashMessage    = null;
        $this->urlBuilder      = null;
        $this->twig            = null;
        $this->controller      = null;
        parent::tearDown();
    }


    /**
     * @tests EditCategory::execute on edit mode
     */
    public function testExecuteEdit()
    {
        $this->request->method('get')->willReturn('1');
        $category = $this->createMock(\Wonders\Category::class);
        $this->categoryService->method('getCategory')->willReturn($category);
        $this->twig->method('render')->willReturn('content');
        $this->assertEquals('content', $this->controller->execute());
    }

    /**
     * @tests EditCategory::execute on edit mode with not valid category
     */
    public function testExecuteEditError()
    {
        $this->request->method('get')->willReturn('1');
        $this->categoryService->method('getCategory')->willReturn(null);
        $this->flashMessage->expects($this->once())->method('addErrorMessage');
        $this->responseFactory->method('create')->willReturn('redirect');
        $this->assertEquals('redirect', $this->controller->execute());
    }

    /**
     * @tests EditCategory::execute on edit mode
     */
    public function testExecuteNew()
    {
        $this->request->method('get')->willReturn(null);
        $category = $this->createMock(\Wonders\Category::class);
        $this->categoryFactory->expects($this->once())->method('create')->willReturn($category);
        $this->twig->method('render')->willReturn('content');
        $this->assertEquals('content', $this->controller->execute());
    }
}
