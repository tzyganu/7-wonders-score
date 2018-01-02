<?php
namespace Controller\Game;

use Controller\GridController;
use Model\Grid;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\TableMap;
use Wonders\GameQuery;

class ListGame extends GridController
{

    protected $grid;
    /**
     * @var string
     */
    protected $selectedMenu = ['games', 'games-list'];

    /**
     * @return array
     */
    public function getGames()
    {
        $games = GameQuery::create()
            ->addAsColumn('id', 'Game.id')
            ->addAsColumn('date', 'Game.date')
            ->useGamePlayerQuery()
                ->usePlayerQuery()
                    ->addAsColumn('player_name', 'GROUP_CONCAT(name SEPARATOR ", ")')
                ->endUse()
                ->groupByGameId()
            ->endUse()
            ->orderByDate(Criteria::DESC)
            ->find()->toArray(null, false, TableMap::TYPE_FIELDNAME);
        return $games;
    }

    /**
     * @return Grid
     */
    protected function getGrid()
    {
        if ($this->grid === null) {
            $grid = new Grid([
                'emptyMessage' => 'There are no games.',
                'id' => 'games',
                'title' => 'Games'
            ]);
            $grid->addColumn(
                new Grid\Column\IntegerColumn([
                    'index' => 'id',
                    'label' => 'Id',
                ])
            );
            $grid->addColumn(
                new Grid\Column\Text([
                    'index' => 'date',
                    'label' => 'Date',
                    'defaultSort' => true,
                    'defaultSortDir' => 'DESC'
                ])
            );
            $grid->addColumn(
                new Grid\Column\Text([
                    'index' => 'player_name',
                    'label' => 'Players',
                ])
            );
            $grid->addColumn(
                new Grid\Column\Edit([
                    'index' => 'id',
                    'label' => 'View',
                    'sortable' => false,
                    'url' => $this->request->getBaseUrl().'/game/view?id=',
                ])
            );

            $grid->addButton(
                'new',
                new Grid\Button('Add New Game', $this->request->getBaseUrl().'/game/new')
            );

            $grid->setRows($this->getGames());
            $this->grid = $grid;
        }
        return $this->grid;
    }
}
