<?php
namespace Controller\WonderGroup;

use Controller\ControllerInterface;
use Controller\GridController;
use Model\Grid;
use Service\WonderGroup;
use Symfony\Component\HttpFoundation\Request;

class ListWonderGroup extends GridController implements ControllerInterface
{
    const GRID_NAME = 'wonder-group';
    /**
     * @var WonderGroup
     */
    private $wonderGroupService;

    /**
     * ListWonderGroup constructor.
     * @param Request $request
     * @param Grid\Loader $gridLoader
     * @param \Twig_Environment $twig
     * @param WonderGroup $wonderGroupService
     * @param string $template
     * @param string $pageTitle
     * @param array $selectedMenu
     */
    public function __construct(
        Request $request,
        Grid\Loader $gridLoader,
        \Twig_Environment $twig,
        WonderGroup $wonderGroupService,
        $template = '',
        $pageTitle = '',
        array $selectedMenu = []
    ) {
        $this->wonderGroupService = $wonderGroupService;
        parent::__construct($request, $gridLoader, $twig, $template, $pageTitle, $selectedMenu);
    }

    /**
     * @return mixed
     */
    protected function getRows()
    {
        return $this->wonderGroupService->getWonderGroups();
    }
}
