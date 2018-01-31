<?php
namespace Test\Unit\Controller\Category;

use Controller\Category\ListCategory;
use Model\Grid;
use Model\Grid\Loader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\Category;
use Symfony\Component\HttpFoundation\Request;

class ListCategoryTest extends TestCase
{
    /**
     * @var Request | MockObject
     */
    private $request;
    /**
     * @var Loader | MockObject
     */
    private $gridLoader;
    /**
     * @var Category | MockObject
     */
    private $categoryService;
    /**
     * @var \Twig_Environment | MockObject
     */
    private $twig;
    /**
     * @var ListCategory
     */
    private $controller;

    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->request          = $this->createMock(Request::class);
        $this->gridLoader       = $this->createMock(Loader::class);
        $this->twig             = $this->createMock(\Twig_Environment::class);
        $this->categoryService  = $this->createMock(Category::class);
        $this->controller       = new ListCategory(
            $this->request,
            $this->gridLoader,
            $this->twig,
            $this->categoryService
        );
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->request          = null;
        $this->gridLoader       = null;
        $this->twig             = null;
        $this->categoryService  = null;
        $this->controller       = null;
        parent::tearDown();
    }

    /**
     * @tests ListCategory::execute check memoizing
     */
    public function testExecute()
    {
        $grid = $this->createMock(Grid::class);
        $this->gridLoader->method('loadGrid')->willReturn($grid);
        $this->categoryService->expects($this->once())->method('getCategories');
        $this->controller->execute();
        $this->controller->execute();
    }
}
