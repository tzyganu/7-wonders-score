<?php

namespace Controller\Game;

use Controller\AuthInterface;
use Controller\BaseController;
use Model\Side;
use Symfony\Component\HttpFoundation\Request;
use Wonders\Category;

class NewGame extends BaseController implements AuthInterface
{
    /**
     * @var \Factory\PlayerQuery
     */
    private $playerQueryFactory;
    /**
     * @var \Factory\WonderQuery
     */
    private $wonderQueryFactory;
    /**
     * @var \Factory\CategoryQuery
     */
    private $categoryQueryFactory;
    /**
     * @var Side
     */
    private $sideModel;

    /**
     * NewGame constructor.
     * @param Request $request
     * @param \Factory\PlayerQuery $playerQueryFactory
     * @param \Factory\WonderQuery $wonderQueryFactory
     * @param \Factory\CategoryQuery $categoryQueryFactory
     * @param Side $sideModel
     */
    public function __construct(
        Request $request,
        \Factory\PlayerQuery $playerQueryFactory,
        \Factory\WonderQuery $wonderQueryFactory,
        \Factory\CategoryQuery $categoryQueryFactory,
        Side $sideModel
    ) {
        $this->playerQueryFactory = $playerQueryFactory;
        $this->wonderQueryFactory = $wonderQueryFactory;
        $this->categoryQueryFactory = $categoryQueryFactory;
        $this->sideModel = $sideModel;
        parent::__construct($request);
    }

    /**
     * @return array
     */
    public function execute()
    {
        return [
            'categories' => $this->getScoringCategories(),
            'existing_players' => $this->getExistingPlayers(),
            'wonders' => $this->getWonders(),
            'sides' => $this->getSides(),
            'game_date' => date('Y-m-d'),
        ];
    }

    /**
     * @return array
     */
    private function getScoringCategories()
    {
        $categories = $this->categoryQueryFactory->create()
            ->orderBySortOrder()
            ->find();
        $categoryArr = [];
        foreach ($categories as $category) {
            /** @var Category $category */
            $categoryArr[] = [
                'name' => $category->getName(),
                'id' => $category->getId(),
                'optional' => $category->getOptional(),
                'icon_class' => $category->getIconClass()
            ];
        }
        return $categoryArr;
    }

    /**
     * @return array
     */
    private function getExistingPlayers()
    {
        $players = $this->playerQueryFactory->create()
            ->orderByName()
            ->find();
        $playerArr = [];
        foreach ($players as $player) {
            $playerArr[] = [
                'name' => $player->getName(),
                'id' => $player->getId()
            ];
        }
        return $playerArr;
    }

    /**
     * @return array
     */
    private function getWonders()
    {
        $wonders = $this->wonderQueryFactory->create()
            ->orderByName()
            ->find();
        $wondersArr = [];
        foreach ($wonders as $wonder) {
            $wondersArr[] = [
                'name' => $wonder->getName(),
                'id' => $wonder->getId()
            ];
        }
        return $wondersArr;
    }

    /**
     * @return array
     */
    private function getSides()
    {
        return $this->sideModel->getSides();
    }
}
