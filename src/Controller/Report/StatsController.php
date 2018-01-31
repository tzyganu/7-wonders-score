<?php
namespace Controller\Report;

use Model\Filter\Factory;
use Model\Grid;
use Symfony\Component\HttpFoundation\Request;

abstract class StatsController
{
    /**
     * @var string
     */
    protected $template;
    /**
     * @var array
     */
    protected $selectedMenu;
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Grid\Factory
     */
    protected $gridFactory;
    /**
     * @var Grid\Column\Factory
     */
    protected $columnFactory;
    /**
     * @var string
     */
    protected $pageTitle;
    /**
     * @var \Twig_Environment
     */
    protected $twig;
    /**
     * @var Factory
     */
    protected $filterFactory;

    /**
     * StatsController constructor.
     * @param Request $request
     * @param Grid\Factory $gridFactory
     * @param Grid\Column\Factory $columnFactory
     * @param \Twig_Environment $twig
     * @param Factory $filterFactory
     * @param string $template
     * @param array $selectedMenu
     * @param string $pageTitle
     */
    public function __construct(
        Request $request,
        \Model\Grid\Factory $gridFactory,
        \Model\Grid\Column\Factory $columnFactory,
        \Twig_Environment $twig,
        Factory $filterFactory,
        $template = '',
        $selectedMenu = [],
        $pageTitle = ''
    ) {
        $this->request       = $request;
        $this->gridFactory   = $gridFactory;
        $this->columnFactory = $columnFactory;
        $this->twig          = $twig;
        $this->filterFactory = $filterFactory;
        $this->template      = $template;
        $this->selectedMenu  = $selectedMenu;
        $this->pageTitle     = $pageTitle;
    }

    /**
     * @return Grid
     */
    abstract protected function getGrid();

    /**
     * @return array
     */
    abstract protected function getFilterKeys();

    /**
     * @return mixed
     */
    protected function getFilters()
    {
        return $this->request->get('search', null);
    }

    /**
     * @return array
     */
    public function getAllVars()
    {
        $vars = [];
        $filters = $this->getFilters();
        $vars['grid'] = ($this->getGrid()) ? $this->getGrid()->render() : '';
        $vars['selectedMenu'] = $this->selectedMenu;
        $vars['page_title'] = $this->pageTitle;
        $filterKeys = $this->getFilterKeys();
        foreach ($filterKeys as $key) {
            $vars['filters'][$key] = $this->filterFactory->create(
                $key,
                [
                    'selected' => isset($filters[$key]) ? $filters[$key] : '',
                    'canGroupSelected' => isset($filters['group_by'][$key]) ? $filters['group_by'][$key] : ''
                ]
            );
        }
        $vars['search'] = [
            'values' => [
                'date' => [
                    'start' => isset($filters['date']['start']) ? $filters['date']['start'] : '',
                    'end' => isset($filters['date']['end']) ? $filters['date']['end'] : ''
                ],
            ]
        ];
        return $vars;
    }

    /**
     * @param $key
     * @return bool
     */
    protected function isSpecificFilter($key)
    {
        $filters = $this->getFilters();
        return (isset($filters[$key]) && is_array($filters[$key]) && count($filters) > 0);
    }

    /**
     * @param $key
     * @return bool
     */
    protected function isGroupBy($key)
    {
        $filters = $this->getFilters();
        return (isset($filters['group_by'][$key])) && $filters['group_by'][$key];
    }

    /**
     * @return string
     */
    public function execute()
    {
        return $this->twig->render(
            $this->template,
            $this->getAllVars()
        );
    }
}
