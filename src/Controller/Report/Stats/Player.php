<?php
namespace Controller\Report\Stats;

use Factory\GamePlayerQuery;
use Factory\PlayerQuery;
use Symfony\Component\HttpFoundation\Request;
use Wonders\GamePlayer;

class Player extends Stats
{
    /**
     * @var PlayerQuery
     */
    protected $playerQueryFactory;

    /**
     * Wonder constructor.
     * @param Request $request
     * @param PlayerQuery $wonderQueryFactory
     * @param GamePlayerQuery $gamePlayerQueryFactory
     */
    public function __construct(
        Request $request,
        PlayerQuery $playerQueryFactory,
        GamePlayerQuery $gamePlayerQueryFactory
    ) {
        $this->playerQueryFactory = $playerQueryFactory;
        parent::__construct($request, $gamePlayerQueryFactory);
    }

    /**
     * @return array
     */
    protected function initRows()
    {
        $players = $this->playerQueryFactory->create()->find();
        $rows = [];
        foreach ($players as $player) {
            $rows[$player->getId()] = [
                'name' => $player->getName(),
                'played' => 0,
                'won' => 0,
                'percentage' => 0,
                'total_points' => 0,
                'average' => 0,
                'max' => null,
                'min' => null,
            ];
        }
        return $rows;
    }

    /**
     * @param GamePlayer $gamePlayer
     * @return string
     */
    protected function getRowKey(GamePlayer $gamePlayer)
    {
        return (string)($gamePlayer->getPlayerId());
    }

    /**
     * @return string
     */
    protected function getGridTitle()
    {
        return 'Player Stats';
    }

    /**
     * @return string
     */
    protected function getGridLabel()
    {
        return 'Player Stats';
    }

}
