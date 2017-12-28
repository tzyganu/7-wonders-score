<?php
namespace Model;

class Side
{
    const SIDE_A = 'A';
    const SIDE_B = 'B';

    /**
     * @return array
     */
    public function getSides()
    {
        return [
            ['id' => self::SIDE_A, 'name' => self::SIDE_A],
            ['id' => self::SIDE_B, 'name' => self::SIDE_B],
        ];
    }
}
