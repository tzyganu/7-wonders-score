<?php
namespace Controller\Report\Stats;

use Controller\ControllerInterface;
use Controller\Report\StatsController;
use Model\Filter\Factory;
use Model\Grid;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Wonders\GamePlayer;

class Wonder extends StatsController implements ControllerInterface
{
    /**
     * @var Grid
     */
    protected $grid;
    /**
     * @var \Service\GamePlayer
     */
    private $gamePlayerService;

    public function __construct(
        Request $request,
        \Model\Grid\Factory $gridFactory,
        \Model\Grid\Column\Factory $columnFactory,
        \Twig_Environment $twig,
        Factory $filterFactory,
        \Service\GamePlayer $gamePlayerService,
        $template = '',
        array $selectedMenu = [],
        $pageTitle = ''
    ) {
        $this->gamePlayerService = $gamePlayerService;
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
            Factory::WONDER_FILTER,
            Factory::SIDE_FILTER,
            Factory::NUMBER_FILTER
        ];
    }

    /**
     * @return string
     */
    private function getGridTitle()
    {
        return 'Stats';
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
    private function canShowWonderColumn()
    {
        return $this->isGroupBy('wonder_id');
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
     * @return bool
     */
    private function canShowWins()
    {
        return $this->canShowPlayerColumn() || $this->canShowWonderColumn() || $this->canShowSideColumn();
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
            if ($this->canShowWonderColumn()) {
                $grid->addColumn(
                    $this->columnFactory->create([
                        'index' => 'wonder_name',
                        'label' => 'Wonder'
                    ])
                );
            }

            if ($this->canShowSideColumn()) {
                $grid->addColumn(
                    $this->columnFactory->create([
                        'index' => 'side',
                        'label' => 'Side'
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
                    'label' => 'Games Played',
                ])
            );
            if ($this->canShowWins()) {
                $grid->addColumn(
                    $this->columnFactory->create([
                        'type' => 'integer',
                        'index' => 'won',
                        'label' => 'Games Won',
                        'defaultSort' => true,
                        'defaultSortDir' => 'DESC'
                    ])
                );
                $grid->addColumn(
                    $this->columnFactory->create([
                        'type' => 'percentage',
                        'index' => 'percentage',
                        'label' => 'Win %',
                        'defaultSort' => true,
                        'defaultSortDir' => 'DESC'
                    ])
                );
            }
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
                    'label' => 'Average',
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
        if ($this->isSpecificFilter('wonder_id')) {
            $queryFilters['WonderId'] = [$filters['wonder_id'], Criteria::IN];
        }
        if ($this->isSpecificFilter('side')) {
            $queryFilters['Side'] = [$filters['side'], Criteria::IN];
        }
        if (isset($filters['date'])) {
            $queryFilters['_game']['date'] = $filters['date'];
        }
        if (isset($filters['player_count']) && !empty($filters['player_count'])) {
            $queryFilters['_game']['player_count'] = [$filters['player_count'], Criteria::IN];
        }
        $gamePlayers = $this->gamePlayerService->getGamePlayers($queryFilters);
        $rows = [];
        foreach ($gamePlayers as $gamePlayer) {
            /** @var GamePlayer $gamePlayer */
            $key = $this->getRowKey($gamePlayer);
            if (!isset($rows[$key])) {
                $initRow = [];
                if ($this->canShowPlayerColumn()) {
                    $initRow['player_name'] = $gamePlayer->getPlayer()->getName();
                }
                if ($this->canShowWonderColumn()) {
                    $wonder = $gamePlayer->getWonder();
                    $initRow['wonder_name'] = ($wonder) ? $wonder->getName() : '-- Not set --';
                }
                if ($this->canShowSideColumn()) {
                    $initRow['side'] = $gamePlayer->getSide();
                }
                if ($this->canShowWins()) {
                    $initRow['won'] = 0;
                }
                if ($this->canShowPlayerCountColumn()) {
                    $initRow['player_count'] = $gamePlayer->getGame()->getPlayerCount();
                }
                $initRow['played'] = 0;
                $initRow['total_points'] = 0;
                $initRow['min'] = PHP_INT_MAX;
                $initRow['max'] = PHP_INT_MIN;
                $rows[$key] = $initRow;
            }
            //update values if needed
            $rows[$key]['played']++;
            if ($this->canShowWins() && $gamePlayer->getPlace() == 1) {
                $rows[$key]['won']++;
            }
            $points = $gamePlayer->getPoints();
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
            if ($this->canShowWins()) {
                $rows[$key]['percentage'] = $row['won'] * 100 / $row['played'];
            }
            $rows[$key]['average'] = $row['total_points'] / $row['played'];
        }
        return $rows;
    }

    /**
     * determing grouping
     *
     * @param GamePlayer $row
     * @return string
     */
    private function getRowKey(GamePlayer $row)
    {
        $keyParts = ['_'];
        if ($this->isGroupBy('player_id')) {
            $keyParts[] = $row->getPlayerId();
        }
        if ($this->isGroupBy('wonder_id')) {
            $keyParts[] = $row->getWonderId();
        }
        if ($this->isGroupBy('side')) {
            $keyParts[] = $row->getSide();
        }
        if ($this->isGroupBy('player_count')) {
            $keyParts[] = $row->getGame()->getPlayerCount();
        }
        return implode('_', $keyParts);
    }
}
