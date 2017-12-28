<?php
namespace Controller\Player;

use Controller\GridController;
use Model\Grid;
use Wonders\PlayerQuery;

class ListPlayer extends GridController
{
    /**
     * @var Grid
     */
    protected $grid;
    /**
     * @var string
     */
    protected $selectedMenu = 'players';

    /**
     * @return Grid
     */
    protected function getGrid()
    {
        if ($this->grid === null) {
            $grid = new Grid([
                'emptyMessage' => 'There are no players',
                'id' => 'players',
                'title' => 'Players'
            ]);
            $grid->addColumn(
                new Grid\Column\IntegerColumn([
                    'index' => 'getId',
                    'label' => 'Id'
                ])
            );
            $grid->addColumn(
                new Grid\Column\Text([
                    'index' => 'getName',
                    'label' => 'Name',
                    'defaultSort' => true
                ])
            );
            $grid->addColumn(
                new Grid\Column\Edit([
                    'index' => 'getId',
                    'label' => 'Edit',
                    'sortable' => false,
                    'url' => $this->request->getBaseUrl().'/player/edit?id='
                ])
            );

            $grid->addButton(
                'new',
                new Grid\Button('Add New Player', $this->request->getBaseUrl().'/player/new')
            );

            $grid->setRows(PlayerQuery::create()->find());
            $this->grid = $grid;
        }
        return $this->grid;
    }
}
