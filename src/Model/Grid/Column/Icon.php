<?php
namespace Model\Grid\Column;

use Model\Grid\Column;

class Icon extends Column
{
    /**
     * @param $value
     * @return string
     */
    public function formatValue($value)
    {
        return ($value) ? '<span class="'.$value.'"></span>' : '';
    }
}
