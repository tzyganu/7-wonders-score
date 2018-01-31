<?php
namespace Controller;

use Model\Grid;
use Symfony\Component\HttpFoundation\Request;

abstract class GridController implements ControllerInterface
{
    const GRID_NAME = 'grid';
    /**
     * @var Grid
     */
    protected $grid;
    /**
     * @var Grid\Loader
     */
    protected $gridLoader;
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var \Twig_Environment
     */
    protected $twig;
    /**
     * @var string
     */
    protected $template;
    /**
     * @var array
     */
    protected $selectedMenu;
    /**
     * @var string
     */
    protected $pageTitle;

    /**
     * ListGame constructor.
     * @param Request $request
     * @param Grid\Loader $gridLoader
     * @param \Twig_Environment $twig
     * @param $template
     * @param $pageTitle
     * @param array $selectedMenu
     */
    public function __construct(
        Request $request,
        Grid\Loader $gridLoader,
        \Twig_Environment $twig,
        $template = '',
        $pageTitle = '',
        array $selectedMenu = []
    ) {
        $this->gridLoader   = $gridLoader;
        $this->request      = $request;
        $this->twig         = $twig;
        $this->template     = $template;
        $this->selectedMenu = $selectedMenu;
        $this->pageTitle    = $pageTitle;
    }

    /**
     * @return array
     */
    abstract protected function getRows();

    /**
     * @return string
     */
    public function execute()
    {
        return $this->twig->render(
            $this->template,
            [
                'page_title' => $this->pageTitle,
                'selectedMenu' => $this->selectedMenu,
                'content' => $this->getGrid()->render()
            ]
        );
    }

    /**
     * @return Grid
     */
    protected function getGrid()
    {
        if ($this->grid === null) {
            $grid = $this->gridLoader->loadGrid(static::GRID_NAME);
            $grid->setRows($this->getRows());
            $this->grid = $grid;
        }
        return $this->grid;
    }
}
