<?php
namespace Test\Unit\Model;

use Model\Side;
use PHPUnit\Framework\TestCase;

class SideTest extends TestCase
{
    /**
     * @tests Side::getSides()
     */
    public function getSides()
    {
        $side = new Side();
        $sides = $side->getSides();
        $this->assertEquals(2, count($sides));
    }
}
