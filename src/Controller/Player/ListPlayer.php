<?php
namespace Controller\Player;

use Controller\ControllerInterface;
use Controller\GridController;
use Model\Grid;
use Service\Player;
use Symfony\Component\HttpFoundation\Request;

class ListPlayer extends GridController implements ControllerInterface
{
    const GRID_NAME = 'player';
    /**
     * @var Player
     */
    private $playerService;

    /**
     * ListPlayer constructor.
     * @param Request $request
     * @param Grid\Loader $gridLoader
     * @param \Twig_Environment $twig
     * @param Player $playerService
     * @param string $template
     * @param string $pageTitle
     * @param array $selectedMenu
     */
    public function __construct(
        Request $request,
        Grid\Loader $gridLoader,
        \Twig_Environment $twig,
        Player $playerService,
        $template = '',
        $pageTitle = '',
        array $selectedMenu = []
    ) {
        $this->playerService = $playerService;
        parent::__construct($request, $gridLoader, $twig, $template, $pageTitle, $selectedMenu);
    }

    /**
     * @return mixed
     */
    public function getRows()
    {
        return $this->playerService->getPlayers();
    }
}
