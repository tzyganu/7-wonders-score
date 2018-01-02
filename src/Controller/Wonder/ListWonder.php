<?php
namespace Controller\Wonder;

use Controller\GridController;
use Model\Grid;
use Wonders\WonderQuery;

class ListWonder extends GridController
{
    /**
     * @var Grid
     */
    protected $grid;
    /**
     * @var string
     */
    protected $selectedMenu = ['wonders', 'wonders-list'];

    /**
     * @return Grid
     */
    protected function getGrid()
    {
        if ($this->grid === null) {
            $grid = new Grid([
                'emptyMessage' => 'There are no wonders',
                'id' => 'wonders',
                'title' => 'Wonders'
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
                    'url' => $this->request->getBaseUrl().'/wonder/edit?id='
                ])
            );

            $grid->addButton(
                'new',
                new Grid\Button('Add New Wonder', $this->request->getBaseUrl().'/wonder/new')
            );

            $grid->setRows(WonderQuery::create()->find());
            $this->grid = $grid;
        }
        return $this->grid;
    }
}
