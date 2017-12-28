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
     * @var string
     */
    protected $emptyMessage = 'There are no records';

    /**
     * Grid constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $fields = ['emptyMessage', 'id', 'title', 'useDataTable'];
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
     * @return string
     */
    public function getDataTableConfig()
    {
        $config = [];
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
        return json_encode($config);
    }

}
