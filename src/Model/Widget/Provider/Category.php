<?php
namespace Model\Widget\Provider;

use Model\Query\ScoreQueryFactory;
use Model\UrlBuilder;
use Model\WidgetFactory;
use Service\Player;

abstract class Category implements ProviderInterface
{
    /**
     * @var \Service\Category
     */
    protected $categoryService;
    /**
     * @var ScoreQueryFactory
     */
    protected $scoreQueryFactory;
    /**
     * @var Player
     */
    protected $playerService;
    /**
     * @var WidgetFactory
     */
    protected $widgetFactory;
    /**
     * @var UrlBuilder
     */
    protected $urlBuilder;
    /**
     * @var string
     */
    protected $expression;

    /**
     * Category constructor.
     * @param \Service\Category $categoryService
     * @param Player $playerService
     * @param ScoreQueryFactory $scoreQueryFactory
     * @param WidgetFactory $widgetFactory
     * @param UrlBuilder $urlBuilder
     */
    public function __construct(
        \Service\Category $categoryService,
        Player $playerService,
        ScoreQueryFactory $scoreQueryFactory,
        WidgetFactory $widgetFactory,
        UrlBuilder $urlBuilder
    ) {
        $this->categoryService   = $categoryService;
        $this->scoreQueryFactory = $scoreQueryFactory;
        $this->playerService     = $playerService;
        $this->widgetFactory     = $widgetFactory;
        $this->urlBuilder        = $urlBuilder;
    }

    public function getWidgets()
    {
        $widgets = [];
        $categories = $this->getCategories();
        $scores = $this->getScoresByCategories($this->expression);
        foreach ($scores as $categoryId => $score) {
            /** @var \Wonders\Category $category */
            $category = $categories[$categoryId];
            $value = number_format($score['result'], 2, '.', '');
            $widgets[] = $this->widgetFactory->create([
                'options' => [
                    'label' => implode(', ', $score['player']),
                    'value' => $category->getName() . ' :' . $value,
                    'icon' => $category->getIconClass(),
                    'link' => $this->urlBuilder->getUrl('report/category')
                ]
            ]);
        }
        return $widgets;
    }

    public function getScoresByCategories($expression)
    {
        /** @var \Wonders\Player[] $players */
        $players = $this->getPlayers();
        $scores = $this->scoreQueryFactory->create()
            ->select(['player_id', 'category_id'])
            ->addAsColumn('result', $expression)
            ->groupByPlayerId()
            ->groupByCategoryId()
            ->find();
        $max = [];
        foreach ($scores as $score) {
            $categoryId = $score['category_id'];
            if (!isset($max[$categoryId])) {
                $max[$categoryId]['result'] = PHP_INT_MIN;
                $max[$categoryId]['player'] = [];
            }
            $value = $score['result'];
            if ($value > $max[$categoryId]['result']) {
                $max[$categoryId]['result'] = $value;
                $max[$categoryId]['player'] = [$players[$score['player_id']]->getName()];
            } elseif ($value == $max[$categoryId]['result']) {
                $max[$categoryId]['player'][] = $players[$score['player_id']]->getName();
            }
        }
        return $max;
    }

    /**
     * @return \Wonders\Category[]
     */
    protected function getCategories()
    {
        $categories = [];
        foreach ($this->categoryService->getCategories() as $category) {
            $categories[$category->getId()] = $category;
        }
        return $categories;
    }

    /**
     * @return \Wonders\Player[]
     */
    protected function getPlayers()
    {
        $players = [];
        foreach ($this->playerService->getPlayers() as $player) {
            /** @var \Wonders\Player $player */
            $players[$player->getId()] = $player;
        }
        return $players;
    }
}
