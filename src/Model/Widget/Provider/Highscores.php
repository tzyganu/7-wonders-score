<?php
namespace Model\Widget\Provider;

use Model\Query\PlayerQueryFactory;
use Model\UrlBuilder;
use Model\WidgetFactory;

class Highscores implements ProviderInterface
{
    /**
     * @var WidgetFactory
     */
    private $widgetFactory;
    /**
     * @var PlayerQueryFactory
     */
    private $playerQueryFactory;
    /**
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * Highscores constructor.
     * @param WidgetFactory $widgetFactory
     * @param PlayerQueryFactory $playerQueryFactory
     * @param UrlBuilder $urlBuilder
     */
    public function __construct(
        WidgetFactory $widgetFactory,
        PlayerQueryFactory $playerQueryFactory,
        UrlBuilder $urlBuilder
    ) {
        $this->widgetFactory      = $widgetFactory;
        $this->playerQueryFactory = $playerQueryFactory;
        $this->urlBuilder         = $urlBuilder;
    }

    public function getWidgets()
    {
        $widgets = [];
        $mostWins = $this->getMostWins();
        if ($mostWins) {
            $widgets[] = $this->widgetFactory->create([
                'options' => [
                    'label' => $mostWins['names'],
                    'value' => "Most wins: " . $mostWins['value'],
                    'icon' => 'fa fa-user',
                    'class' => 'bg-teal',
                    'link' => $this->urlBuilder->getUrl('report/wonder') //TODO: add params
                ]
            ]);
        }
        $highscore = $this->getHighScore();
        if ($highscore) {
            $widgets[] = $this->widgetFactory->create([
                'options' => [
                    'label' => $highscore['names'],
                    'value' => "Highscore: " . $highscore['value'],
                    'icon' => 'fa fa-user',
                    'class' => 'bg-teal',
                    'link' => $this->urlBuilder->getUrl('report/wonder') //TODO: add params
                ]
            ]);
        }
        return $widgets;
    }

    private function getMostWins()
    {
        $players = $this->playerQueryFactory->create()
            ->useGamePlayerQuery()
            ->addAsColumn('wins', 'COUNT(1)')
            ->addAsColumn('name', 'Player.Name')
            ->filterByPlace(1)
            ->endUse()
            ->groupById()
            ->addDescendingOrderByColumn('wins')
            ->find();
        $max = null;
        $names = [];
        foreach ($players as $player) {
            $wins = $player->getVirtualColumn('wins');
            if ($max === null) {
                $max = $wins;
                $names[] = $player->getName();
            } elseif ($wins == $max) {
                $names[] = $player->getName();
            } elseif ($wins < $max) {
                break;
            }
        }
        if ($max === null) {
            return null;
        }
        return [
            'value' => $max,
            'names' => implode(', ', $names)
        ];
    }

    protected function getHighScore()
    {
        $players = $this->playerQueryFactory->create()
            ->useGamePlayerQuery()
            ->addAsColumn('score', 'MAX(points)')
            ->addAsColumn('name', 'Player.Name')
            ->endUse()
            ->groupById()
            ->addDescendingOrderByColumn('score')
            ->find();
        $max = null;
        $names = [];
        foreach ($players as $player) {
            $score = $player->getVirtualColumn('score');
            if ($max === null) {
                $max = $score;
                $names[] = $player->getName();
            } elseif ($score == $max) {
                $names[] = $player->getName();
            } elseif ($score < $max) {
                break;
            }
        }
        if ($max === null) {
            return null;
        }
        return [
            'value' => $max,
            'names' => implode(', ', $names)
        ];
    }
}
