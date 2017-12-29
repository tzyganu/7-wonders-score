<?php
namespace Model;

use Model\Grid\Button;
use Model\Grid\Column;

class Grid
{
    /**
     * @var Column[]
     */
    protected $columns = [];
    /**
     * @var Button[]
     */
    protected $buttons = [];

    /**
     * @var array
     */
    protected $rows = [];
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $id;

    /**
     * @var bool
     */
    protected $useDataTable = true;
    /**
     * @var bool
     */
    protected $showPaging = true;
    /**
     * @var bool
     */
    protected $showSearch = true;
    /**
     * @var bool
     */
    protected $showSorting = true;
    /**
     * @var bool
     */
    protected $showPagingAll = true;
    /**
     * @var array
     */
    protected $pagingValues = [10, 20, 50, 100];
    /**
     * @var string
     */
    protected $emptyMessage = 'There are no records';

    /**
     * Grid constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $fields = ['emptyMessage', 'id', 'title', 'useDataTable', 'showPaging', 'showSearch', 'showSorting', 'showPagingAll', 'pagingValues'];
        foreach ($fields as $field) {
            if (isset($options[$field])) {
                $method = 'set'.ucfirst($field);
                $this->$method($options[$field]);
            }
        }
    }

    /**
     * @return string
     */
    public function getEmptyMessage()
    {
        return $this->emptyMessage;
    }

    /**
     * @param string $emptyMessage
     */
    public function setEmptyMessage($emptyMessage)
    {
        $this->emptyMessage = $emptyMessage;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param $key
     * @param Button $button
     */
    public function addButton($key, Button $button)
    {
        $this->buttons[$key] = $button;
    }

    /**
     * @return Button[]
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * @param Column $column
     */
    public function addColumn(Column $column)
    {
        $this->columns[] = $column;
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param array $rows
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
    }

    /**
     * @return bool
     */
    public function isUseDataTable()
    {
        return $this->useDataTable;
    }

    /**
     * @param bool $useDataTable
     */
    public function setUseDataTable($useDataTable)
    {
        $this->useDataTable = $useDataTable;
    }

    /**
     * @return bool
     */
    public function isShowPaging()
    {
        return $this->showPaging;
    }

    /**
     * @param bool $showPaging
     */
    public function setShowPaging($showPaging)
    {
        $this->showPaging = $showPaging;
    }

    /**
     * @return bool
     */
    public function isShowSearch()
    {
        return $this->showSearch;
    }

    /**
     * @param bool $showSearch
     */
    public function setShowSearch($showSearch)
    {
        $this->showSearch = $showSearch;
    }

    /**
     * @return bool
     */
    public function isShowSorting()
    {
        return $this->showSorting;
    }

    /**
     * @param bool $showSorting
     */
    public function setShowSorting($showSorting)
    {
        $this->showSorting = $showSorting;
    }

    /**
     * @return bool
     */
    public function isShowPagingAll()
    {
        return $this->showPagingAll;
    }

    /**
     * @param bool $showPagingAll
     */
    public function setShowPagingAll($showPagingAll)
    {
        $this->showPagingAll = $showPagingAll;
    }

    /**
     * @return array
     */
    public function getPagingValues()
    {
        return $this->pagingValues;
    }

    /**
     * @param array $pagingValues
     */
    public function setPagingValues($pagingValues)
    {
        $this->pagingValues = $pagingValues;
    }

    /**
     * @return string
     */
    public function getDataTableConfig()
    {
        $config = [];
        if (!$this->isShowSorting()) {
            $config['sorting'] = false;
        } else {
            $nonSortable = [];
            $defaultOrderColumn = null;
            $defaultOrder = null;
            foreach ($this->getColumns() as $key => $column) {
                if (!$column->isSortable()) {
                    $nonSortable[] = $key;
                }
                if ($column->isDefaultSort()) {
                    $defaultOrderColumn = $key;
                    $defaultOrder = $column->getDefaultSortDir();
                }
            }
            if (count($nonSortable)) {
                $config['columnDefs'] = [
                    ['orderable' => false, 'targets' => $nonSortable]
                ];
            }
            if ($defaultOrderColumn) {
                $config['order'] = [
                    [$defaultOrderColumn, strtolower($defaultOrder)]

                ];
            }
        }
        if (!$this->isShowPaging()) {
            $config['paging'] = false;
        } else {
            $pagingValues = $this->getPagingValues();
            $pagingLabels = $this->getPagingValues();
            if ($this->isShowPagingAll()) {
                $pagingValues[] = -1;
                $pagingLabels[] = 'All';
            }
            $config['lengthMenu'] = [$pagingValues, $pagingLabels];
        }
        if (!$this->isShowSearch()) {
            $config['search'] = false;
        }
        return json_encode($config);
    }

}
