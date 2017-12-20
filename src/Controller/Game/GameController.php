<?php
namespace Controller\Game;

use Controller\BaseController;
use Factory\GameQuery;
use Symfony\Component\HttpFoundation\Request;

abstract class GameController extends BaseController
{
    /**
     * @var \Factory\GameQuery
     */
    protected $gameQueryFactory;

    /**
     * @param Request $request
     * @param GameQuery $gameQueryFactory
     */
    public function __construct(
        Request $request,
        GameQuery $gameQueryFactory
    ) {
        $this->gameQueryFactory = $gameQueryFactory;
        parent::__construct($request);
    }
}
