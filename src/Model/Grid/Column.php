<?php
namespace Model\Grid;

abstract class Column
{
    /**
     * @var string
     */
    protected $label = 'You forgot to add the label on this column';

    /**
     * @var bool
     */
    protected $sortable = true;
    /**
     * @var bool
     */
    protected $defaultSort = false;

    /**
     * @var string
     */
    protected $defaultSortDir;

    /**
     * @var string
     */
    protected $index;
    /**
     * @var string
     */
    protected $iconClass = '';

    /**
     * Column constructor.
     * @param array $options
     */
    public function __construct(array $options) {
        $fields = $this->getConstructorOptionsFields();
        foreach ($fields as $field) {
            if (isset($options[$field])) {
                $method = 'set'.ucfirst($field);
                $this->$method($options[$field]);
            }
        }
    }

    /**
     * @return array
     */
    protected function getConstructorOptionsFields()
    {
        return ['index', 'label', 'sortable', 'defaultSort', 'defaultSortDir', 'iconClass'];

    }

    /**
     * @param $value
     * @return mixed
     */
    abstract protected function formatValue($value);

    /**
     * @param $row
     * @param null $default
     * @return null
     * @throws \Exception
     */
    public function render($row, $default = null)
    {
        $realValue = $default;
        if (is_array($row)) {
            if (isset($row[$this->getIndex()])) {
                $realValue = $row[$this->getIndex()];
            }
        } elseif (is_object($row)) {
            $method = $this->getIndex();
            if (method_exists($row, $method)) {
                $realValue = $row->$method();
            }
        } else {
            throw new \Exception("Row must be object or array");
        }

        return $this->formatValue($realValue);
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param mixed $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return bool
     */
    public function isSortable()
    {
        return $this->sortable;
    }

    /**
     * @param bool $sortable
     */
    public function setSortable($sortable)
    {
        $this->sortable = $sortable;
    }

    /**
     * @return bool
     */
    public function isDefaultSort()
    {
        return $this->defaultSort;
    }

    /**
     * @param bool $defaultSort
     */
    public function setDefaultSort($defaultSort)
    {
        $this->defaultSort = (bool)$defaultSort;
    }

    /**
     * @return string
     */
    public function getDefaultSortDir()
    {
        return (in_array(strtoupper($this->defaultSortDir), ['ASC', 'DESC']))
            ? strtoupper($this->defaultSortDir)
            : 'ASC';
    }

    /**
     * @param string $defaultSortDir
     */
    public function setDefaultSortDir($defaultSortDir)
    {
        $this->defaultSortDir = $defaultSortDir;
    }

    /**
     * @return string
     */
    public function getIconClass()
    {
        return $this->iconClass;
    }

    /**
     * @param string $iconClass
     */
    public function setIconClass($iconClass)
    {
        $this->iconClass = $iconClass;
    }
}
