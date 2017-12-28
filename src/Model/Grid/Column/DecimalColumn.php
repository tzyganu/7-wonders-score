<?php
namespace Model\Grid\Column;

use Model\Grid\Column;

class DecimalColumn extends Column
{
    /**
     * @param $value
     * @return string
     */
    public function formatValue($value)
    {
        return number_format($value, 2, '.', '');
    }
}
