<?php
namespace Model\Grid\Column;

use Model\Grid\Column;

class Text extends Column
{
    /**
     * @param $value
     * @return string
     */
    public function formatValue($value)
    {
        return (string)$value;
    }
}
