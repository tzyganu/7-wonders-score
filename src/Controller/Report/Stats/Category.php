<?php
namespace Controller\Report\Stats;

use Controller\ReportController;
use Model\Grid;
use Propel\Runtime\ActiveQuery\Criteria;
use Wonders\CategoryQuery;
use Wonders\PlayerQuery;
use Wonders\Score;

class Category extends ReportController
{
    /**
     * @var string
     */
    protected $template = 'report/category.html.twig';
    /**
     * @var array
     */
    protected $cache = [];
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @param $vars
     * @return array
     */
    public function getAllVars($vars)
    {
        $filters = $this->getFilters();
        $vars = parent::getAllVars($vars);
        $vars['search'] = [
            'players' => $this->getPlayers(),
            'categories' => $this->getCategories(),
            'values' => [
                'date' => [
                    'start' => isset($filters['date']['start']) ? $filters['date']['start'] : '',
                    'end' => isset($filters['date']['end']) ? $filters['date']['end'] : ''
                ],
                'player_id' => isset($filters['player_id']) ? $filters['player_id'] : '',
                'category_id' => isset($filters['category_id']) ? $filters['category_id'] : '',
            ]
        ];

        return $vars;
    }

    /**
     * @return mixed
     */
    protected function getFilters()
    {
        return $this->request->get('search', null);
    }

    /**
     * @return string
     */
    protected function getGridTitle()
    {
        return 'Score Category Stats';
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
     * @return bool
     */
    protected function canShowPlayerColumn()
    {
        return $this->isSpecificFilter('player_id');
    }

    /**
     * @return bool
     */
    protected function canShowCategoryColumn()
    {
        return $this->isSpecificFilter('category_id');
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
            $grid = new Grid([
                'emptyMessage' => 'There is no data so far for this report',
                'id' => 'stats',
                'title' => $this->getGridTitle(),
                'pagingValues' => [20, 40, 100, 200]
            ]);
            if ($this->canShowPlayerColumn()) {
                $grid->addColumn(
                    new Grid\Column\Text([
                        'index' => 'player_name',
                        'label' => 'Player'
                    ])
                );
            }
            if ($this->canShowCategoryColumn()) {
                $grid->addColumn(
                    new Grid\Column\Text([
                        'index' => 'category_name',
                        'label' => 'Score Category'
                    ])
                );
            }
            $grid->addColumn(
                new Grid\Column\IntegerColumn([
                    'index' => 'played',
                    'label' => 'Times played',
                ])
            );
            $grid->addColumn(
                new Grid\Column\IntegerColumn([
                    'index' => 'total_points',
                    'label' => 'Total Points',
                ])
            );
            $grid->addColumn(
                new Grid\Column\DecimalColumn([
                    'index' => 'average',
                    'label' => 'Average',
                ])
            );
            $grid->addColumn(
                new Grid\Column\IntegerColumn([
                    'index' => 'max',
                    'label' => 'Max Points',
                ])
            );
            $grid->addColumn(
                new Grid\Column\IntegerColumn([
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
    protected function getRows()
    {
        $scores = \Wonders\ScoreQuery::create();
        $filters = $this->getFilters();
        if ($this->isSpecificFilter('player_id')) {
            $scores->filterByPlayerId($filters['player_id'], Criteria::IN);
        }
        if ($this->isSpecificFilter('category_id')) {
            $scores->filterByCategoryId($filters['category_id'], Criteria::IN);
        }
        if (isset($filters['date']['start']) && !empty($filters['date']['start'])) {
            $scores->useGameQuery()
                ->filterByDate($filters['date']['start'], Criteria::GREATER_EQUAL)
                ->endUse();
        }
        if (isset($filters['date']['end']) && !empty($filters['date']['end'])) {
            $scores->useGameQuery()
                ->filterByDate($filters['date']['end'], Criteria::LESS_EQUAL)
                ->endUse();
        }
        $players = $this->getPlayers();
        $playersById = [];
        foreach ($players as $player) {
            $playersById[$player['value']] = $player['label'];
        }

        $categories = $this->getCategories();
        $categoriesById = [];
        foreach ($categories as $category) {
            $categoriesById[$category['value']] = $category['label'];
        }
        $rows = [];
        foreach ($scores as $score) {
            /** @var Score $score */
            $key = $this->getRowKey($score);
            if (!isset($rows[$key])) {
                $initRow = [];
                if ($this->canShowPlayerColumn()) {
                    $initRow['player_name'] = $playersById[$score->getPlayerId()];
                }
                if ($this->canShowCategoryColumn()) {
                    $initRow['category_name'] = $categoriesById[$score->getCategoryId()];
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
     * determing grouping
     *
     * @param Score $row
     * @return string
     */
    protected function getRowKey(Score $row)
    {
        $keyParts = ['_'];
        if ($this->isSpecificFilter('player_id')) {
            $keyParts[] = $row->getPlayerId();
        }
        if ($this->isSpecificFilter('category_id')) {
            $keyParts[] = $row->getCategoryId();
        }
        return implode('_', $keyParts);
    }

    /**
     * @return mixed
     */
    protected function getPlayers()
    {
        if (!isset($this->cache['players'])) {
            $players = PlayerQuery::create()->orderByName()->find();
            $values = [];
            foreach ($players as $player) {
                $values[] = [
                    'label' => $player->getName(),
                    'value' => $player->getId()
                ];
            }
            $this->cache['players'] = $values;
        }
        return $this->cache['players'];
    }

    /**
     * @return mixed
     */
    protected function getCategories()
    {
        if (!isset($this->cache['categories'])) {
            $categories = CategoryQuery::create()->orderBySortOrder()->find();
            $values = [];
            foreach ($categories as $category) {
                $values[] = [
                    'label' => $category->getName(),
                    'value' => $category->getId()
                ];
            }
            $this->cache['categories'] = $values;
        }
        return $this->cache['categories'];
    }
}
