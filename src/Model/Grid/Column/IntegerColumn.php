<?php
namespace Model\Grid\Column;

use Model\Grid\Column;

class IntegerColumn extends Column
{
    /**
     * @param $value
     * @return string
     */
    public function formatValue($value)
    {
        return (int)$value;
    }
}
