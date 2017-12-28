<?php
namespace Controller;

use Model\Widget;
use Wonders\GameQuery;
use Wonders\PlayerQuery;

class IndexController extends OutputController
{
    protected $template = 'index.html.twig';
    protected $selectedMenu = 'dashboard';

    public function execute()
    {
        return $this->render([
            'widgets' => $this->getWidgets()
        ]);
    }

    protected function getLatestPlayer()
    {
        $player = PlayerQuery::create()
            ->addDescendingOrderByColumn('id')
            ->findOne();
        return $player;
    }

    protected function getLatestGame()
    {
        $game = GameQuery::create()
            ->addDescendingOrderByColumn('id')
            ->findOne();
        return $game;
    }

    protected function getMostWins()
    {
        $player = PlayerQuery::create()
            ->useGamePlayerQuery()
            ->addAsColumn('wins', 'COUNT(1)')
            ->addAsColumn('name', 'Player.Name')
            ->filterByPlace(1)
            ->endUse()
            ->groupById()
            ->addDescendingOrderByColumn('wins')
            ->findOne();
        return $player;
    }

    protected function getHighScore()
    {
        $player = PlayerQuery::create()
            ->useGamePlayerQuery()
            ->addAsColumn('score', 'MAX(points)')
            ->addAsColumn('name', 'Player.Name')
            ->endUse()
            ->groupById()
            ->addDescendingOrderByColumn('score')
            ->findOne();
        return $player;
    }

    /**
     * @return Widget[]
     */
    protected function getWidgets()
    {
        $widgets = [];

        $latestGame = $this->getLatestGame();
        if ($latestGame) {
            $widget = new Widget([
                'label' => 'Latest game',
                'value' => $latestGame->getId(). ' : '.$latestGame->getDate('Y-m-d'),
                'icon' => 'fa fa-gamepad',
                'class' => 'bg-green',
                'link' => $this->request->getBaseUrl().'/game/view?id='.$latestGame->getId()
            ]);
            $widgets[] = $widget;
        }

        $latestPlayer = $this->getLatestPlayer();
        if ($latestPlayer) {
            $widget = new Widget([
                'label' => 'Latest player',
                'value' => $latestPlayer->getName(),
                'icon' => 'fa fa-user',
                'class' => 'bg-aqua',
                'link' => $this->request->getBaseUrl().'/player/list'
            ]);
            $widgets[] = $widget;
        }

        $mostWins = $this->getMostWins();
        if ($mostWins) {
            $widget = new Widget([
                'label' => 'Most Wins',
                'value' => $mostWins->getVirtualColumn('name'). ': '.$mostWins->getVirtualColumn('wins'),
                'icon' => 'fa fa-user',
                'class' => 'bg-yellow',
                'link' => $this->request->getBaseUrl().'/stats/player'
            ]);
            $widgets[] = $widget;
        }

        $highScore = $this->getHighScore();
        if ($highScore) {
            $widget = new Widget([
                'label' => 'Highscore',
                'value' => $highScore->getVirtualColumn('name'). ': '.$highScore->getVirtualColumn('score'),
                'icon' => 'fa fa-user',
                'class' => 'bg-red',
                'link' => $this->request->getBaseUrl().'/stats/player'
            ]);
            $widgets[] = $widget;
        }


        return $widgets;
    }

}
