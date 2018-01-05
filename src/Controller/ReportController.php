<?php
namespace Controller;

abstract class ReportController extends GridController
{
    /**
     * @var string
     */
    protected $selectedMenu = 'reports';

    /**
     * @return array
     */
    protected function getPlayerCounts()
    {
        $counts = [];
        for ($i = 3; $i<=8;$i++) {
            $counts[] = [
                'label' => $i,
                'value' => $i
            ];
        }
        return $counts;
    }
}
