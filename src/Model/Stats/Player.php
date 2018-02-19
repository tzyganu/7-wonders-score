<?php
namespace Model\Stats;

use Model\Grid\Factory as GridFactory;
use Model\Grid\Column\Factory as ColumnFactory;
use Propel\Runtime\ActiveQuery\Criteria;

class Player
{
    /**
     * @var GridFactory
     */
    private $gridFactory;
    /**
     * @var ColumnFactory
     */
    private $columnFactory;

    /**
     * Player constructor.
     * @param GridFactory $gridFactory
     * @param ColumnFactory $columnFactory
     */
    public function __construct(
        GridFactory $gridFactory,
        ColumnFactory $columnFactory
    ) {
        $this->gridFactory      = $gridFactory;
        $this->columnFactory    = $columnFactory;

    }

    public function getBasicStats(\Wonders\Player $player)
    {
        $gamePlayers = $player->getGamePlayers();
        $rank = [];
        $played = 0;
        $total = 0;
        $last = 0;
        $high = $low = null;
        foreach ($gamePlayers as $gamePlayer) {
            $played++;
            $points = $gamePlayer->getPoints();
            if (!isset($rank[$gamePlayer->getPlace()])) {
                $rank[$gamePlayer->getPlace()] = 0;
            }
            $rank[$gamePlayer->getPlace()]++;
            $total += $points;
            if ($gamePlayer->getPlace() == $gamePlayer->getGame()->getPlayerCount()) {
                $last++;
            }
            if ($high === null || $points > $high) {
                $high = $points;
            }
            if ($low === null || $points < $low) {
                $low = $points;
            }
        }
        ksort($rank);
        $grid = $this->gridFactory->create([
            'title' => 'Basic Stats',
            'id' => 'basic-stats',
            'useDataTable' => false
        ]);
        $grid->addColumn(
            $this->columnFactory->create([
                'type' => 'integer',
                'index' => 'played',
                'label' => 'Games played',
            ])
        );
        $grid->addColumn(
            $this->columnFactory->create([
                'type' => 'percentage',
                'index' => 'percent',
                'label' => 'Win Percentage',
            ])
        );
        $grid->addColumn(
            $this->columnFactory->create([
                'type' => 'integer',
                'index' => 'total',
                'label' => 'Total Points',
            ])
        );
        $grid->addColumn(
            $this->columnFactory->create([
                'type' => 'decimal',
                'index' => 'average',
                'label' => 'Average Points',
            ])
        );
        $grid->addColumn(
            $this->columnFactory->create([
                'type' => 'integer',
                'index' => 'high',
                'label' => 'Highscore',
            ])
        );
        $grid->addColumn(
            $this->columnFactory->create([
                'type' => 'integer',
                'index' => 'low',
                'label' => 'Low score',
            ])
        );
        foreach ($rank as $number => $value) {
            $grid->addColumn(
                $this->columnFactory->create([
                    'type' => 'integer',
                    'index' => 'rank_'.$number,
                    'label' => ($number == 1) ? 'Wins' : "Ranked ". $number,
                ])
            );
        }
        $grid->addColumn(
            $this->columnFactory->create([
                'type' => 'integer',
                'index' => 'last',
                'label' => 'Last Place',
            ])
        );
        $wins = isset($rank[1]) ? $rank[1] : 0;
        $winPercentage = ($played == 0) ? 0 : $wins * 100 / $played;
        $row = [
            'played' => $played,
            'percent' => $winPercentage,
            'total' => $total,
            'average' => ($played == 0) ? 0 : $total / $played,
            'last' => $last,
            'high' => $high,
            'low' => $low
        ];
        foreach ($rank as $number => $value) {
            $row['rank_'.$number] = $value;
        }
        $grid->setRows([$row]);
        return $grid->render();
    }

    /**
     * @param \Wonders\Player $player
     * @return array
     */
    public function getBasicCategoryStats(\Wonders\Player $player)
    {
        $categories = [];
        $total = 0;
        foreach ($player->getScores() as $score) {
            $category = $score->getCategory();
            $total += $score->getValue();
            $categoryId = $score->getCategoryId();
            if (!isset($categories[$categoryId])) {
                $categories[$categoryId] = [
                    'name' => $score->getCategory()->getName(),
                    'y' => 0,
                    'color' => $category->getColor()
                ];
            }
            $categories[$categoryId]['y'] += $score->getValue();
        }
        foreach ($categories as $key => $category) {
            $categories[$key]['data'] = $category['y'] * 100 / $total;
        }
        return $categories;
    }

    /**
     * @param \Wonders\Player $player
     * @return array
     */
    public function getBasicWonderStats(\Wonders\Player $player)
    {
        $wonders = [];
        $total = 0;
        foreach ($player->getGamePlayers() as $gamePlayer) {
            $wonder = $gamePlayer->getWonder();
            if (!$wonder) {
                continue;
            }
            $total += $gamePlayer->getPoints();
            $wonderId = $wonder->getId();
            if (!isset($wonders[$wonderId])) {
                $wonders[$wonderId] = [
                    'name' => $wonder->getName(),
                    'y' => 0,
                    'count' => 0
                ];
            }
            $wonders[$wonderId]['y'] += $gamePlayer->getPoints();
            $wonders[$wonderId]['count'] ++;
        }
        foreach ($wonders as $key => $wonder) {
            $wonders[$key]['name'] .= ' ('.$wonder['count'].' Games)';
            $wonders[$key]['data'] = $wonder['y'] * 100 / $total;
            unset($wonders[$key]['count']);
        }
        return $wonders;
    }

    /**
     * Not really proud of this code, but it works.
     * @param \Wonders\Player $player
     * @return array
     */
    public function getCategoryPerRank(\Wonders\Player $player)
    {
        $wondersCache = [];
        $gamePlayers = $player->getGamePlayers();
        $wondersByGame = [];
        foreach ($gamePlayers as $gamePlayer) {
            $wonderId = $gamePlayer->getWonderId();
            if (!isset($wondersCache[$wonderId])) {
                $wonder = $gamePlayer->getWonder();
                if (!$wonder) {
                    $wonderName = 'Wonder : --Not Specified--';
                    $side = $gamePlayer->getSide();
                    if ($side) {
                        $wonderName .= '('.$side.')';
                    }
                    $wondersByGame[$gamePlayer->getGameId()] = $wonderName;
                    continue;
                } else {
                    $wondersCache[$wonderId] = $wonder->getName();
                }
            }
            $wonderName = $wondersCache[$wonderId];
            $side = $gamePlayer->getSide();
            if ($side) {
                $wonderName .= ': '.$side;
            }
            $wondersByGame[$gamePlayer->getGameId()] = $wonderName;
        }
        $categoriesCache = [];
        $chartData = [];
        $rankByGames = [];
        foreach ($player->getGamePlayers() as $gamePlayer) {
            $rank = $gamePlayer->getPlace();
            $game = $gamePlayer->getGameId();
            $rankByGames[$game] = $rank;
        }
        foreach ($player->getScores() as $score) {
            $gameId = $score->getGameId();
            $rank = $rankByGames[$gameId];
            $categoryId = $score->getCategoryId();
            if (!isset($chartData[$rank]['categories'][$categoryId])) {
                $categoryName = isset($categoriesCache[$categoryId])
                    ? $categoriesCache[$categoryId]
                    : $score->getCategory()->getName();
                $categoriesCache[$categoryId] = $categoryName;
                $chartData[$rank]['categories'][$categoryId] = $categoryName;
            }
            if (!isset($chartData[$rank]['values'][$gameId])) {
                $chartData[$rank]['values'][$gameId] = [
                    'name' => 'Game:'. $gameId .'-'.$wondersByGame[$gameId],
                    'data' => []
                ];
            }
            $chartData[$rank]['values'][$gameId]['data'][$categoryId] = [
                'y' => $score->getValue(),
                'color' => $score->getCategory()->getColor()
            ];
        }
        foreach ($chartData as $rank => $data) {
            $categories = $data['categories'];
            ksort($categories);
            foreach ($chartData[$rank]['values'] as $gameId => $gameStats) {
                $scores = $gameStats['data'];
                ksort($scores);
                $chartData[$rank]['values'][$gameId]['data'] = array_values($scores);
            }
            $chartData[$rank]['categories'] = array_values($categories);
            $chartData[$rank]['values'] = array_values($chartData[$rank]['values']);
        }
        ksort($chartData);
        return $chartData;
    }
}
