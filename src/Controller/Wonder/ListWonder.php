<?php
namespace Controller\Wonder;

use Controller\ControllerInterface;
use Controller\GridController;
use Model\Grid;
use Service\Wonder;
use Symfony\Component\HttpFoundation\Request;

class ListWonder extends GridController implements ControllerInterface
{
    const GRID_NAME = 'wonder';
    /**
     * @var Wonder
     */
    private $wonderService;

    /**
     * ListWonder constructor.
     * @param Request $request
     * @param Grid\Loader $gridLoader
     * @param \Twig_Environment $twig
     * @param Wonder $wonderService
     * @param string $template
     * @param string $pageTitle
     * @param array $selectedMenu
     */
    public function __construct(
        Request $request,
        Grid\Loader $gridLoader,
        \Twig_Environment $twig,
        Wonder $wonderService,
        $template = '',
        $pageTitle = '',
        array $selectedMenu = []
    ) {
        $this->wonderService = $wonderService;
        parent::__construct($request, $gridLoader, $twig, $template, $pageTitle, $selectedMenu);
    }

    /**
     * @return mixed
     */
    public function getRows()
    {
        return $this->wonderService->getWonders();
    }
}
