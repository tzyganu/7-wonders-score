<?php
namespace Controller\Category;

use Controller\BaseController;
use Factory\CategoryQuery;
use Symfony\Component\HttpFoundation\Request;

abstract class CategoryController extends BaseController
{
    /**
     * @var \Factory\CategoryQuery
     */
    protected $categoryQueryFactory;

    /**
     * @param Request $request
     * @param CategoryQuery $categoryQueryFactory
     */
    public function __construct(
        Request $request,
        CategoryQuery $categoryQueryFactory
    ) {
        $this->categoryQueryFactory = $categoryQueryFactory;
        parent::__construct($request);
    }
}
