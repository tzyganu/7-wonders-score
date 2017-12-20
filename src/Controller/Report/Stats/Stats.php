<?php
namespace Controller\Report\Stats;

use Controller\ReportController;
use Factory\GamePlayerQuery;
use Symfony\Component\HttpFoundation\Request;
use Wonders\GamePlayer;

abstract class Stats extends ReportController
{
    /**
     * @var array
     */
    protected $rows = [];
    /**
     * @var GamePlayerQuery
     */
    protected $gamePlayerQueryFactory;

    /**
     * Stats constructor.
     * @param Request $request
     * @param GamePlayerQuery $gamePlayerQueryFactory
     */
    public function __construct(
        Request $request,
        GamePlayerQuery $gamePlayerQueryFactory
    ) {
        $this->gamePlayerQueryFactory = $gamePlayerQueryFactory;
        parent::__construct($request);
    }

    /**
     * @return array
     */
    abstract protected function initRows();

    /**
     * @return array
     */
    protected function getColumns()
    {
        return [
            ['label' => 'Name'],
            ['label' => 'Games Played'],
            ['label' => 'Games Won'],
            ['label' => 'Win %'],
            ['label' => 'Total Points'],
            ['label' => 'Average'],
            ['label' => 'Max points'],
            ['label' => 'Min points']
        ];
    }

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
     * @return string
     */
    abstract protected function getGridLabel();

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
    protected function populateRows()
    {
        $rows = $this->initRows();
        $gamePlayers = $this->gamePlayerQueryFactory->create()->find();
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
            $rows[$key]['percentage'] = (($played != 0)
                    ? sprintf('%.2f', $row['won'] * 100 / $played)
                    : 0).'%';
            $rows[$key]['average'] = $played != 0
                ? sprintf('%.2f', $row['total_points'] / $played)
                : 0;
        }
        //cleanup
        foreach ($rows as $key => $row) {
            if ($row['played'] == 0) {
                unset($rows[$key]);
            }
        }
        $this->rows = $rows;
    }

    /**
     * @return array
     */
    public function execute()
    {
        $this->populateRows();
        return [
            'title' => $this->getGridTitle(),
            'label' => $this->getGridLabel(),
            'columns' => $this->getColumns(),
            'rows' => $this->rows
        ];
    }
}
