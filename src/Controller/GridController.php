<?php
namespace Controller;

use Model\Grid;

abstract class GridController extends OutputController
{
    /**
     * @var string
     */
    protected $template = 'grid.html.twig';

    /**
     * @return Grid
     */
    abstract protected function getGrid();

    /**
     * @return string
     */
    public function execute()
    {
        return $this->twig->render($this->template, $this->getAllVars(['grid' => $this->getGrid()]));
    }

}
