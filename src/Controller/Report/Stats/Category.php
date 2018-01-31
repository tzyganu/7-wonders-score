<?php
namespace Controller\Report\Stats;

use Controller\ControllerInterface;
use Controller\Report\StatsController;
use Model\Filter\Factory;
use Model\Grid;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Wonders\Score;

class Category extends StatsController implements ControllerInterface
{
    /**
     * @var Grid
     */
    protected $grid;
    /**
     * @var \Service\Score
     */
    private $scorerService;

    /**
     * Category constructor.
     * @param Request $request
     * @param Grid\Factory $gridFactory
     * @param Grid\Column\Factory $columnFactory
     * @param \Twig_Environment $twig
     * @param Factory $filterFactory
     * @param \Service\Score $scoreService
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
        \Service\Score $scoreService,
        $template = '',
        array $selectedMenu = [],
        $pageTitle = ''
    ) {
        $this->scorerService = $scoreService;
        parent::__construct(
            $request,
            $gridFactory,
            $columnFactory,
            $twig,
            $filterFactory,
            $template,
            $selectedMenu,
            $pageTitle
        );
    }

    /**
     * @return array
     */
    protected function getFilterKeys()
    {
        return [
            Factory::PLAYER_FILTER,
            Factory::CATEGORY_FILTER,
            Factory::NUMBER_FILTER
        ];
    }

    /**
     * @return string
     */
    private function getGridTitle()
    {
        return 'Score Category Stats';
    }

    /**
     * @return bool
     */
    private function canShowPlayerColumn()
    {
        return $this->isGroupBy('player_id');
    }

    /**
     * @return bool
     */
    private function canShowCategoryColumn()
    {
        return $this->isGroupBy('category_id');
    }

    /**
     * @return bool
     */
    private function canShowPlayerCountColumn()
    {
        return $this->isGroupBy('player_count');
    }

    /**
     * @return bool
     */
    private function canShowSideColumn()
    {
        return $this->isGroupBy('side');
    }

    /**
     * @return Grid|null
     */
    protected function getGrid()
    {
        $filters = $this->getFilters();
        if ($filters === null) {
            return null;
        }
        if ($this->grid === null) {
            $grid = $this->gridFactory->create([
                'emptyMessage' => 'There is no data so far for this report',
                'id' => 'stats',
                'title' => $this->getGridTitle(),
                'pagingValues' => [20, 40, 100, 200]
            ]);
            if ($this->canShowPlayerColumn()) {
                $grid->addColumn(
                    $this->columnFactory->create([
                        'index' => 'player_name',
                        'label' => 'Player'
                    ])
                );
            }
            if ($this->canShowCategoryColumn()) {
                $grid->addColumn(
                    $this->columnFactory->create([
                        'index' => 'category_name',
                        'label' => 'Score Category'
                    ])
                );
            }

            if ($this->canShowPlayerCountColumn()) {
                $grid->addColumn(
                    $this->columnFactory->create([
                        'type' => 'integer',
                        'index' => 'player_count',
                        'label' => '# of Players'
                    ])
                );
            }
            $grid->addColumn(
                $this->columnFactory->create([
                    'type' => 'integer',
                    'index' => 'played',
                    'label' => 'Categories Played',
                ])
            );
            $grid->addColumn(
                $this->columnFactory->create([
                    'type' => 'integer',
                    'index' => 'total_points',
                    'label' => 'Total Points',
                ])
            );
            $grid->addColumn(
                $this->columnFactory->create([
                    'type' => 'decimal',
                    'index' => 'average',
                    'label' => 'Average per category',
                ])
            );
            $grid->addColumn(
                $this->columnFactory->create([
                    'type' => 'integer',
                    'index' => 'max',
                    'label' => 'Max Points',
                ])
            );
            $grid->addColumn(
                $this->columnFactory->create([
                    'type' => 'integer',
                    'index' => 'min',
                    'label' => 'Min Points',
                ])
            );
            $grid->setRows(array_values($this->getRows()));
            $this->grid = $grid;
        }
        return $this->grid;
    }

    /**
     * get grid rows
     *
     * @return array
     */
    private function getRows()
    {
        $queryFilters = [];
        $filters = $this->getFilters();
        if ($this->isSpecificFilter('player_id')) {
            $queryFilters['PlayerId'] = [$filters['player_id'], Criteria::IN];
        }
        if ($this->isSpecificFilter('category_id')) {
            $queryFilters['CategoryId'] = [$filters['category_id'], Criteria::IN];
        }
        if (isset($filters['date'])) {
            $queryFilters['_game']['date'] = $filters['date'];
        }
        if (isset($filters['player_count']) && !empty($filters['player_count'])) {
            $queryFilters['_game']['player_count'] = [$filters['player_count'], Criteria::IN];
        }
        $gamePlayers = $this->scorerService->getScores($queryFilters);
        $rows = [];
        foreach ($gamePlayers as $score) {
            /** @var Score $score$ */
            $key = $this->getRowKey($score);
            if (!isset($rows[$key])) {
                $initRow = [];
                if ($this->canShowPlayerColumn()) {
                    $initRow['player_name'] = $score->getPlayer()->getName();
                }
                if ($this->canShowCategoryColumn()) {
                    $initRow['category_name'] = $score->getCategory()->getName();
                }
                if ($this->canShowPlayerCountColumn()) {
                    $initRow['player_count'] = $score->getGame()->getPlayerCount();
                }
                $initRow['played'] = 0;
                $initRow['total_points'] = 0;
                $initRow['min'] = PHP_INT_MAX;
                $initRow['max'] = PHP_INT_MIN;
                $rows[$key] = $initRow;
            }
            //update values if needed
            $rows[$key]['played']++;
            $points = $score->getValue();
            $rows[$key]['total_points'] += $points;
            if ($points > $rows[$key]['max']) {
                $rows[$key]['max'] = $points;
            }
            if ($points < $rows[$key]['min']) {
                $rows[$key]['min'] = $points;
            }
        }

        //calculate other values
        foreach ($rows as $key => $row) {
            $rows[$key]['average'] = $row['total_points'] / $row['played'];
        }
        return $rows;
    }

    /**
     * determine grouping
     *
     * @param Score $row
     * @return string
     */
    private function getRowKey(Score $row)
    {
        $keyParts = ['_'];
        if ($this->isGroupBy('player_id')) {
            $keyParts[] = $row->getPlayerId();
        }
        if ($this->isGroupBy('category_id')) {
            $keyParts[] = $row->getCategoryId();
        }
        if ($this->isGroupBy('player_count')) {
            $keyParts[] = $row->getGame()->getPlayerCount();
        }
        return implode('_', $keyParts);
    }
}
