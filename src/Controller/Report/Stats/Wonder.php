<?php
namespace Controller\Report\Stats;

use Controller\ReportController;
use Model\Grid;
use Model\Side;
use Propel\Runtime\ActiveQuery\Criteria;
use Wonders\GamePlayer;
use Wonders\PlayerQuery;
use Wonders\WonderQuery;

class Wonder extends ReportController
{
    /**
     * @var string
     */
    protected $template = 'report/wonder.html.twig';
    /**
     * @var array
     */
    protected $cache = [];
    /**
     * @var Grid
     */
    protected $grid;

    protected $selectedMenu = ['reports', 'reports-wonder'];

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
            'wonders' => $this->getWonders(),
            'player_count' => $this->getPlayerCounts(),
            'sides' => $this->getSides(),
            'values' => [
                'date' => [
                    'start' => isset($filters['date']['start']) ? $filters['date']['start'] : '',
                    'end' => isset($filters['date']['end']) ? $filters['date']['end'] : ''
                ],
                'player_id' => isset($filters['player_id']) ? $filters['player_id'] : '',
                'wonder_id' => isset($filters['wonder_id']) ? $filters['wonder_id'] : '',
                'side' => isset($filters['side']) ? $filters['side'] : '',
                'player_count' => isset($filters['player_count']) ? $filters['player_count'] : '',
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
        return 'Stats';
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
    protected function canShowWonderColumn()
    {
        return $this->isSpecificFilter('wonder_id');
    }

    /**
     * @return bool
     */
    protected function canShowSideColumn()
    {
        return $this->isSpecificFilter('side');
    }

    /**
     * @return bool
     */
    protected function canShowWins()
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
            if ($this->canShowWonderColumn()) {
                $grid->addColumn(
                    new Grid\Column\Text([
                        'index' => 'wonder_name',
                        'label' => 'Wonder'
                    ])
                );
            }

            if ($this->canShowSideColumn()) {
                $grid->addColumn(
                    new Grid\Column\Text([
                        'index' => 'side',
                        'label' => 'Side'
                    ])
                );
            }
            $grid->addColumn(
                new Grid\Column\IntegerColumn([
                    'index' => 'played',
                    'label' => 'Games Played',
                ])
            );
            if ($this->canShowWins()) {
                $grid->addColumn(
                    new Grid\Column\IntegerColumn([
                        'index' => 'won',
                        'label' => 'Games Won',
                        'defaultSort' => true,
                        'defaultSortDir' => 'DESC'
                    ])
                );
                $grid->addColumn(
                    new Grid\Column\Percentage([
                        'index' => 'percentage',
                        'label' => 'Win %',
                        'defaultSort' => true,
                        'defaultSortDir' => 'DESC'
                    ])
                );
            }
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
        $gamePlayers = \Wonders\GamePlayerQuery::create();
        $filters = $this->getFilters();
        if ($this->isSpecificFilter('player_id')) {
            $gamePlayers->filterByPlayerId($filters['player_id'], Criteria::IN);
        }
        if ($this->isSpecificFilter('wonder_id')) {
            $gamePlayers->filterByWonderId($filters['wonder_id'], Criteria::IN);
        }
        if ($this->isSpecificFilter('side')) {
            $gamePlayers->filterBySide($filters['side'], Criteria::IN);
        }
        if (isset($filters['date']['start']) && !empty($filters['date']['start'])) {
            $gamePlayers->useGameQuery()
                ->filterByDate($filters['date']['start'], Criteria::GREATER_EQUAL)
                ->endUse();
        }
        if (isset($filters['date']['end']) && !empty($filters['date']['end'])) {
            $gamePlayers->useGameQuery()
                ->filterByDate($filters['date']['end'], Criteria::LESS_EQUAL)
                ->endUse();
        }
        if (isset($filters['player_count']) && !empty($filters['player_count'])) {
            $gamePlayers->useGameQuery()
                ->filterByPlayerCount($filters['player_count'], Criteria::IN)
                ->endUse();
        }
        $players = $this->getPlayers();
        $playersById = [];
        foreach ($players as $player) {
            $playersById[$player['value']] = $player['label'];
        }

        $wonders = $this->getWonders();
        $wondersById = [];
        foreach ($wonders as $wonder) {
            $wondersById[$wonder['value']] = $wonder['label'];
        }
        $rows = [];
        foreach ($gamePlayers as $gamePlayer) {
            /** @var GamePlayer $gamePlayer */
            $key = $this->getRowKey($gamePlayer);
            if (!isset($rows[$key])) {
                $initRow = [];
                if ($this->canShowPlayerColumn()) {
                    $initRow['player_name'] = $playersById[$gamePlayer->getPlayerId()];
                }
                if ($this->canShowWonderColumn()) {
                    $initRow['wonder_name'] = $wondersById[$gamePlayer->getWonderId()];
                }
                if ($this->canShowSideColumn()) {
                    $initRow['side'] = $gamePlayer->getSide();
                }
                if ($this->canShowWins()) {
                    $initRow['won'] = 0;
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
    protected function getRowKey(GamePlayer $row)
    {
        $keyParts = ['_'];
        if ($this->isSpecificFilter('player_id')) {
            $keyParts[] = $row->getPlayerId();
        }
        if ($this->isSpecificFilter('wonder_id')) {
            $keyParts[] = $row->getWonderId();
        }
        if ($this->isSpecificFilter('side')) {
            $keyParts[] = $row->getSide();
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
    protected function getWonders()
    {
        if (!isset($this->cache['wonders'])) {
            $wonders = WonderQuery::create()->orderByName()->find();
            $values = [];
            foreach ($wonders as $wonder) {
                $values[] = [
                    'label' => $wonder->getName(),
                    'value' => $wonder->getId()
                ];
            }
            $this->cache['wonders'] = $values;
        }
        return $this->cache['wonders'];
    }

    /**
     * @return mixed
     */
    protected function getSides()
    {
        if (!isset($this->cache['sides'])) {
            $sideModel = new Side();
            $values = [];
            foreach ($sideModel->getSides() as $side) {
                $values[] = [
                    'label' => $side['name'],
                    'value' => $side['id']
                ];
            }
            $this->cache['sides'] = $values;
        }
        return $this->cache['sides'];
    }
}
