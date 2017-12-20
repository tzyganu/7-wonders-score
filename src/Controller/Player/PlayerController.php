<?php
namespace Controller\Player;

use Controller\BaseController;
use Factory\PlayerQuery;
use Symfony\Component\HttpFoundation\Request;

abstract class PlayerController extends BaseController
{
    /**
     * @var \Factory\PlayerQuery
     */
    protected $playerQueryFactory;

    /**
     * @param Request $request
     * @param PlayerQuery $playerQueryFactory
     */
    public function __construct(
        Request $request,
        PlayerQuery $playerQueryFactory
    ) {
        $this->playerQueryFactory = $playerQueryFactory;
        parent::__construct($request);
    }
}
