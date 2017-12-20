<?php
namespace Controller\Report\Stats;

use Factory\GamePlayerQuery;
use Factory\WonderQuery;
use Model\Side;
use Symfony\Component\HttpFoundation\Request;
use Wonders\GamePlayer;

class Wonder extends Stats
{
    /**
     * @var WonderQuery
     */
    protected $wonderQueryFactory;
    /**
     * @var Side
     */
    protected $sideModel;

    /**
     * Wonder constructor.
     * @param Request $request
     * @param WonderQuery $wonderQueryFactory
     * @param GamePlayerQuery $gamePlayerQueryFactory
     * @param Side $sideModel
     */
    public function __construct(
        Request $request,
        WonderQuery $wonderQueryFactory,
        GamePlayerQuery $gamePlayerQueryFactory,
        Side $sideModel
    ) {
        $this->wonderQueryFactory = $wonderQueryFactory;
        $this->sideModel = $sideModel;
        parent::__construct($request, $gamePlayerQueryFactory);
    }

    /**
     * @return array
     */
    protected function initRows()
    {
        $wonders = $this->wonderQueryFactory->create()->find();
        $rows = [];
        $sides = $this->sideModel->getSides();
        foreach ($wonders as $wonder) {
            foreach ($sides as $side) {
                $rows[$wonder->getId() . '_'.$side['id']] = [
                    'name' => $wonder->getName() . ' '. $side['name'],
                    'played' => 0,
                    'won' => 0,
                    'percentage' => 0,
                    'total_points' => 0,
                    'average' => 0,
                    'max' => null,
                    'min' => null,
                ];
            }
        }
        return $rows;
    }

    /**
     * @param GamePlayer $gamePlayer
     * @return bool
     */
    protected function validate(GamePlayer $gamePlayer)
    {
        return (!empty($gamePlayer->getSide()) && !empty($gamePlayer->getWonderId()));
    }

    /**
     * @param GamePlayer $gamePlayer
     * @return string
     */
    protected function getRowKey(GamePlayer $gamePlayer)
    {
        return $gamePlayer->getWonderId().'_'.$gamePlayer->getSide();
    }

    /**
     * @return string
     */
    protected function getGridTitle()
    {
        return 'Wonder Stats';
    }

    /**
     * @return string
     */
    protected function getGridLabel()
    {
        return 'Wonder Stats';
    }

}
