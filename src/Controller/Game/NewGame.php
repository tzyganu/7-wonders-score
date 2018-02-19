<?php

namespace Controller\Game;

use Controller\AuthInterface;
use Controller\ControllerInterface;
use Model\PlayerCount;
use Model\ScienceScore;
use Model\Side;
use Propel\Runtime\Map\TableMap;
use Service\Player;
use Service\Wonder;
use Service\WonderGroup;
use Symfony\Component\HttpFoundation\Request;
use Wonders\Game;
use Wonders\WonderGroupWonder;

class NewGame implements AuthInterface, ControllerInterface
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var \Service\Category
     */
    private $categoryService;
    /**
     * @var Player
     */
    private $playerService;
    /**
     * @var Wonder
     */
    private $wonderService;
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var Side
     */
    private $side;
    /**
     * @var string
     */
    private $template;
    /**
     * @var array
     */
    private $selectedMenu;
    /**
     * @var string
     */
    private $pageTitle;
    /**
     * @var WonderGroup
     */
    private $wonderGroupService;

    private $scienceScore;

    /**
     * NewGame constructor.
     * @param Request $request
     * @param \Service\Category $categoryService
     * @param Player $playerService
     * @param Wonder $wonderService
     * @param \Twig_Environment $twig
     * @param Side $side
     * @param WonderGroup $wonderGroupService
     * @param string $template
     * @param array $selectedMenu
     * @param string $pageTitle
     */
    public function __construct(
        Request $request,
        \Service\Category $categoryService,
        Player $playerService,
        Wonder $wonderService,
        \Twig_Environment $twig,
        Side $side,
        WonderGroup $wonderGroupService,
        ScienceScore $scienceScore,
        $template = '',
        $selectedMenu = [],
        $pageTitle = ''
    ) {
        $this->request              = $request;
        $this->categoryService      = $categoryService;
        $this->playerService        = $playerService;
        $this->wonderService        = $wonderService;
        $this->twig                 = $twig;
        $this->side                 = $side;
        $this->wonderGroupService   = $wonderGroupService;
        $this->scienceScore         = $scienceScore;
        $this->template             = $template;
        $this->selectedMenu         = $selectedMenu;
        $this->pageTitle            = $pageTitle;
    }

    /**
     * @return int
     */
    private function getDefaultPlayers()
    {
        if ($default = $this->request->get('players')) {
            return (int)$default;
        }
        return PlayerCount::DEFAULT_PLAYERS;
    }

    /**
     * @return array
     */
    private function getWonders()
    {
        $wonders = [];
        foreach ($this->wonderService->getWonders() as $wonder) {
            /** @var \Wonders\Wonder $wonder */
            $item = [
                'id' => $wonder->getId(),
                'name' => $wonder->getName(),
                'groups' => []
            ];
            $groups = $wonder->getWonderGroupWonders();
            if ($groups) {
                foreach ($groups as $group) {
                    /** @var WonderGroupWonder $group */
                    $item['groups'][] = $group->getWonderGroupId();
                }
            }
            $wonders[] = $item;
        }
        return $wonders;
    }

    private function getScienceScoreHtml()
    {
        return $this->scienceScore->getGridHtml();
    }

    /**
     * @return string
     */
    public function execute()
    {
        return $this->twig->render(
            $this->template,
            [
                'categories' => $this->categoryService->getCategories()->toArray(null, false, TableMap::TYPE_FIELDNAME),
                'existing_players' => $this->playerService->getPlayers()
                    ->toArray(null, false, TableMap::TYPE_FIELDNAME),
                'wonders' => $this->getWonders(),
                'sides' => $this->side->getSides(),
                'wonderGroups' => $this->wonderGroupService->getWonderGroups(),
                'game_date' => date('Y-m-d'),
                'min_players' => PlayerCount::MIN_PLAYERS,
                'max_players' => PlayerCount::MAX_PLAYERS,
                'default_players' => $this->getDefaultPlayers(),
                'page_title' => $this->pageTitle,
                'selectedMenu' => $this->selectedMenu,
                'scienceScore' => $this->getScienceScoreHtml()
            ]
        );
    }
}
