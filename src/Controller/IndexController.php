<?php
namespace Controller;

use Model\Widget;
use Wonders\Category;
use Wonders\CategoryQuery;
use Wonders\GameQuery;
use Wonders\Player;
use Wonders\PlayerQuery;
use Wonders\ScoreQuery;

class IndexController extends OutputController
{
    protected $template = 'index.html.twig';
    protected $selectedMenu = 'dashboard';
    /**
     * @var Player
     */
    protected $players;
    /**
     * @var Category
     */
    protected $categories;

    public function execute()
    {
        return $this->render([
            'widgetGroups' => $this->getWidgetGroups()
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
     * @return array|null|Player
     */
    public function getAllPlayers()
    {
        if ($this->players === null) {
            $players = PlayerQuery::create()
                ->find();
            $this->players = [];
            foreach ($players as $player) {
                $this->players[$player->getId()] = $player;
            }
        }
        return $this->players;
    }

    /**
     * @return array|null|Category
     */
    public function getAllCategories()
    {
        if ($this->categories === null) {
            $categories = CategoryQuery::create()
                ->find();
            $this->categories = [];
            foreach ($categories as $category) {
                $this->categories[$category->getId()] = $category;
            }
        }
        return $this->categories;
    }

    public function getScoresByCategories($expression, $alias)
    {
        $players = $this->getAllPlayers();
        $scores = ScoreQuery::create()
            ->select(['player_id', 'category_id'])
            ->addAsColumn($alias, $expression)
            ->groupByPlayerId()
            ->groupByCategoryId()
            ->find();
        $max = [];
        foreach ($scores as $score) {
            $categoryId = $score['category_id'];
            if (!isset($max[$categoryId])) {
                $max[$categoryId][$alias] = PHP_INT_MIN;
                $max[$categoryId]['player'] = [];
            }
            $value = $score[$alias];
            if ($value > $max[$categoryId][$alias]) {
                $max[$categoryId][$alias] = $value;
                $max[$categoryId]['player'] = [$players[$score['player_id']]->getName()];
            } elseif ($value == $max[$categoryId][$alias]) {
                $max[$categoryId]['player'][] = $players[$score['player_id']]->getName();
            }
        }
        return $max;
    }

    /**
     * @return Widget[]
     */
    protected function getWidgetGroups()
    {
        $widgetGroups = [];

        $widgetGroups['highscore'] = [
            'label' => 'High scores',
            'widgetClass' => 'col-lg-6 col-xs-12',
            'widgets' => []
        ];

        $widgetGroups['categories-average'] = [
            'label' => 'Score Categories Averages',
            'widgetClass' => 'col-lg-6 col-xs-12',
            'widgets' => []
        ];

        $widgetGroups['categories-highscore'] = [
            'label' => 'Score Categories High Scores',
            'widgetClass' => 'col-lg-6 col-xs-12',
            'widgets' => []
        ];

        $widgetGroups['latest'] = [
            'label' => 'Latest',
            'widgetClass' => 'col-lg-6 col-xs-12',
            'widgets' => []
        ];

        $latestGame = $this->getLatestGame();
        if ($latestGame) {
            $widget = new Widget([
                'label' => 'Latest game',
                'value' => $latestGame->getId(). ' : '.$latestGame->getDate('Y-m-d'),
                'icon' => 'fa fa-gamepad',
                'class' => 'bg-teal',
                'link' => $this->request->getBaseUrl().'/game/view?id='.$latestGame->getId()
            ]);
            $widgetGroups['latest']['widgets'][] = $widget;
        }

        $latestPlayer = $this->getLatestPlayer();
        if ($latestPlayer) {
            $widget = new Widget([
                'label' => 'Latest player',
                'value' => $latestPlayer->getName(),
                'icon' => 'fa fa-user',
                'class' => 'bg-teal',
                'link' => $this->request->getBaseUrl().'/player/list'
            ]);
            $widgetGroups['latest']['widgets'][] = $widget;
        }

        $mostWins = $this->getMostWins();
        if ($mostWins) {
            $widget = new Widget([
                'label' => 'Most Wins',
                'value' => $mostWins->getVirtualColumn('name'). ': '.$mostWins->getVirtualColumn('wins'),
                'icon' => 'fa fa-user',
                'class' => 'bg-teal',
                'link' => $this->request->getBaseUrl().'/stats/player'
            ]);
            $widgetGroups['highscore']['widgets'][] = $widget;
        }

        $highScore = $this->getHighScore();
        if ($highScore) {
            $widget = new Widget([
                'label' => 'Highscore',
                'value' => $highScore->getVirtualColumn('name'). ': '.$highScore->getVirtualColumn('score'),
                'icon' => 'fa fa-user',
                'class' => 'bg-teal',
                'link' => $this->request->getBaseUrl().'/stats/player'
            ]);
            $widgetGroups['highscore']['widgets'][] = $widget;
        }


        $expressions = [
            [
                'expression' => 'AVG(value)',
                'alias' => 'average',
                'group' => 'categories-average',
                'class' => 'bg-teal'
            ],
            [
                'expression' => 'MAX(value)',
                'alias' => 'max',
                'group' => 'categories-highscore',
                'class' => 'bg-teal'
            ],
        ];
        $categories = $this->getAllCategories();
        foreach ($expressions as $expression) {
            $alias = $expression['alias'];
            $scores = $this->getScoresByCategories($expression['expression'], $alias);
            foreach ($scores as $categoryId => $score) {
                /** @var Category $category */
                $category = $categories[$categoryId];
                $value = number_format($score[$alias], 2, '.', '');
                $widget = new Widget([
                    'label' => implode(', ', $score['player']),
                    'value' => $category->getName() . ' :' . $value,
                    'icon' => $category->getIconClass(),
                    'class' => $expression['class'],
                ]);
                $widgetGroups[$expression['group']]['widgets'][] = $widget;
            }
        }
        return $widgetGroups;
    }

}
