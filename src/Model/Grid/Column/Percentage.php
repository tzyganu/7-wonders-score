<?php
namespace Model\Grid\Column;

class Percentage extends DecimalColumn
{
    /**
     * @param $value
     * @return string
     */
    public function formatValue($value)
    {
        return parent::formatValue($value) .'%';
    }
}
