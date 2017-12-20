<?php
namespace Controller\Wonder;

use Controller\BaseController;
use Factory\WonderQuery;
use Symfony\Component\HttpFoundation\Request;

abstract class WonderController extends BaseController
{
    /**
     * @var \Factory\WonderQuery
     */
    protected $wonderQueryFactory;

    /**
     * @param Request $request
     * @param WonderQuery $wonderQueryFactory
     */
    public function __construct(
        Request $request,
        WonderQuery $wonderQueryFactory
    ) {
        $this->wonderQueryFactory = $wonderQueryFactory;
        parent::__construct($request);
    }
}
