<?php
namespace Controller\Report\Stats;

use Controller\ReportController;
use Factory\GamePlayerQuery;
use Model\Grid;
use Symfony\Component\HttpFoundation\Request;
use Wonders\GamePlayer;

abstract class Stats extends ReportController
{
    /**
     * @var Grid
     */
    protected $grid;
    /**
     * @return array
     */
    abstract protected function initRows();

    /**
     * @param GamePlayer $gamePlayer
     * @return string
     */
    abstract protected function getRowKey(GamePlayer $gamePlayer);

    /**
     * @return string
     */
    abstract protected function getGridTitle();

    /**
     * @param GamePlayer $gamePlayer
     * @return bool
     */
    protected function validate(GamePlayer $gamePlayer)
    {
        return true;
    }

    /**
     * populate rows with data
     */
    protected function getRows()
    {
        $rows = $this->initRows();
        $gamePlayers = \Wonders\GamePlayerQuery::create()->find();
        foreach ($gamePlayers as $gamePlayer) {
            if (!$this->validate($gamePlayer)) {
                continue;
            }
            $key = $this->getRowKey($gamePlayer);
            $rows[$key]['played']++;
            if ($gamePlayer->getPlace() == 1) {
                $rows[$key]['won']++;
            }
            $score = $gamePlayer->getPoints();
            $rows[$key]['total_points'] += $score;
            if ($rows[$key]['max'] === null || $rows[$key]['max'] < $score) {
                $rows[$key]['max'] = $score;
            }
            if ($rows[$key]['min'] === null || $rows[$key]['min'] > $score) {
                $rows[$key]['min'] = $score;
            }
        }
        foreach ($rows as $key => $row) {
            $played = $row['played'];
            $rows[$key]['percentage'] = ($played != 0)
                    ? $row['won'] * 100 / $played
                    : 0;
            $rows[$key]['average'] = ($played != 0)
                ? $row['total_points'] / $played
                : 0;
        }
        //cleanup
        foreach ($rows as $key => $row) {
            if ($row['played'] == 0) {
                unset($rows[$key]);
            }
        }
        return $rows;
    }

    protected function getGrid()
    {
        if ($this->grid === null) {
            $grid = new Grid([
                'emptyMessage' => 'There is no data so far for this report',
                'id' => 'stats',
                'title' => $this->getGridTitle()
            ]);

            $grid->addColumn(
                new Grid\Column\Text([
                    'index' => 'name',
                    'label' => 'Name'
                ])
            );
            $grid->addColumn(
                new Grid\Column\IntegerColumn([
                    'index' => 'played',
                    'label' => 'Games Played',
                ])
            );

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
}
