<?php
namespace Model;

use Model\Grid\Loader;

class ScienceScore
{
    const GRID_NAME = 'science';
    const LIMIT = 7;
    /**
     * @var Loader
     */
    private $gridLoader;

    /**
     * ScienceScore constructor.
     * @param Loader $gridLoader
     */
    public function __construct(
        Loader $gridLoader
    ) {
        $this->gridLoader = $gridLoader;
    }

    /**
     * @return string
     */
    public function getGridHtml()
    {
        $grid = $this->gridLoader->loadGrid(self::GRID_NAME);
        $grid->setRows($this->getRows());
        return $grid->render();
    }

    /**
     * @return int
     */
    private function getLimit()
    {
        return self::LIMIT;
    }

    /**
     * @return array
     */
    private function getRows()
    {
        $rows = [];
        for ($tablet = 0; $tablet <= $this->getLimit(); $tablet++) {
            for ($compass = $tablet; $compass <= $this->getLimit(); $compass++) {
                for ($cog = $compass; $cog <= $this->getLimit(); $cog++) {
                    $rows[] = [
                        'symbol' => $tablet.'-'.$compass.'-'.$cog,
                        'score' => $this->getScore($tablet, $compass, $cog)
                    ];
                }
            }
        }
        return $rows;
    }

    /**
     * @param $table
     * @param $compass
     * @param $cog
     * @return mixed
     */
    private function getScore($table, $compass, $cog)
    {
        $score = min($table, $compass, $cog) * 7;
        foreach ([$table, $compass, $cog] as $symbol) {
            $score += $symbol * $symbol;
        }
        return $score;
    }
}
