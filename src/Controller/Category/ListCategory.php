<?php
namespace Controller\Category;

use Controller\GridController;
use Model\Grid;
use Wonders\CategoryQuery;

class ListCategory extends GridController
{
    protected $grid;
    /**
     * @var string
     */
    protected $selectedMenu = ['categories', 'categories-list'];

    /**
     * @return Grid
     */
    protected function getGrid()
    {
        if ($this->grid === null) {
            $grid = new Grid([
                'emptyMessage' => 'There are no categories',
                'id' => 'score-categories',
                'title' => 'Score Categories'
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
                    'label' => 'Name'
                ])
            );
            $grid->addColumn(
                new Grid\Column\IntegerColumn([
                    'index' => 'getSortOrder',
                    'label' => 'Sort Order',
                    'defaultSort' => true,
                    'defaultSortDir' => 'ASC'
                ])
            );
            $grid->addColumn(
                new Grid\Column\Icon([
                    'index' => 'getIconClass',
                    'label' => 'Icon',
                    'sortable' => false
                ])
            );
            $grid->addColumn(
                new Grid\Column\YesNo([
                    'index' => 'getOptional',
                    'label' => 'Optional',
                    'sortable' => false
                ])
            );
            $grid->addColumn(
                new Grid\Column\Edit([
                    'index' => 'getId',
                    'label' => 'Edit',
                    'sortable' => false,
                    'url' => $this->request->getBaseUrl().'/category/edit?id='
                ])
            );

            $grid->addButton(
                'new',
                new Grid\Button('Add New Score Category', $this->request->getBaseUrl().'/category/new')
            );

            $grid->setRows(CategoryQuery::create()->orderBySortOrder()->find());
            $this->grid = $grid;
        }
        return $this->grid;
    }
}
