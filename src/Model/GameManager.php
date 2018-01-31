<?php
namespace Model;

use Model\Factory\GameCategoryFactory;
use Model\Factory\GameFactory;
use Model\Factory\GamePlayerFactory;
use Model\Factory\PlayerFactory;
use Model\Factory\ScoreFactory;
use Propel\Runtime\ActiveQuery\Criteria;
use Service\Category;
use Service\Game;
use Service\GameCategory;
use Service\GamePlayer;
use Service\Player;
use Service\Score;

class GameManager
{
    /**
     * @var Transaction
     */
    private $transaction;
    /**
     * @var Game
     */
    private $gameService;
    /**
     * @var GameFactory
     */
    private $gameFactory;
    /**
     * @var PlayerFactory
     */
    private $playerFactory;
    /**
     * @var Player
     */
    private $playerService;
    /**
     * @var GamePlayerFactory
     */
    private $gamePlayerFactory;
    /**
     * @var GamePlayer
     */
    private $gamePlayerService;
    /**
     * @var ScoreFactory
     */
    private $scoreFactory;
    /**
     * @var Score
     */
    private $scoreService;
    /**
     * @var Category
     */
    private $categoryService;
    /**
     * @var GameCategory
     */
    private $gameCategoryService;
    /**
     * @var GameCategoryFactory
     */
    private $gameCategoryFactory;

    /**
     * GameManager constructor.
     * @param Transaction $transaction
     * @param Game $gameService
     * @param GameFactory $gameFactory
     * @param Player $playerService
     * @param PlayerFactory $playerFactory
     * @param GamePlayerFactory $gamePlayerFactory
     * @param GamePlayer $gamePlayerService
     * @param ScoreFactory $scoreFactory
     * @param Score $scoreService
     * @param Category $categoryService
     * @param GameCategoryFactory $gameCategoryFactory
     * @param GameCategory $gameCategoryService
     */
    public function __construct(
        Transaction $transaction,
        Game $gameService,
        GameFactory $gameFactory,
        Player $playerService,
        PlayerFactory $playerFactory,
        GamePlayerFactory $gamePlayerFactory,
        GamePlayer $gamePlayerService,
        ScoreFactory $scoreFactory,
        Score $scoreService,
        Category $categoryService,
        GameCategoryFactory $gameCategoryFactory,
        GameCategory $gameCategoryService
    ) {
        $this->transaction         = $transaction;
        $this->gameService         = $gameService;
        $this->gameFactory         = $gameFactory;
        $this->playerService       = $playerService;
        $this->playerFactory       = $playerFactory;
        $this->gamePlayerFactory   = $gamePlayerFactory;
        $this->gamePlayerService   = $gamePlayerService;
        $this->scoreFactory        = $scoreFactory;
        $this->scoreService        = $scoreService;
        $this->categoryService     = $categoryService;
        $this->gameCategoryFactory = $gameCategoryFactory;
        $this->gameCategoryService = $gameCategoryService;
    }

    /**
     * @param array $gameData
     * @param array $playerIds
     * @param array $newPlayers
     * @param array $scoreData
     * @param array $wonders
     * @param array $sides
     * @param array $excludedCategoryIds
     * @return \Wonders\Game
     * @throws \Exception
     */
    public function saveGame(
        array $gameData,
        array $playerIds,
        array $newPlayers,
        array $scoreData,
        array $wonders,
        array $sides,
        array $excludedCategoryIds
    ) {
        try {
            $this->transaction->begin();
            foreach ($playerIds as $key => $playerId) {
                if ($playerId == 0) {
                    $player = $this->playerFactory->create();
                    $player->setName($newPlayers[$key]);
                    $this->playerService->save($player);
                    $playerIds[$key] = $player->getId();
                }
            }
            $game = $this->gameFactory->create();
            $game->setDate($gameData['game_date']);
            $game->setUserId($gameData['user_id']);
            $game->setPlayerCount(count($playerIds));
            $this->gameService->save($game);

            $scoresAndPlace = $this->determineScoreAndPlace($playerIds, $scoreData);
            foreach ($playerIds as $playerKey => $playerId) {
                $gamePlayer = $this->gamePlayerFactory->create();
                $gamePlayer->setGame($game);
                $gamePlayer->setPlayerId($playerId);
                $gamePlayer->setPoints($scoresAndPlace[$playerId]['score']);
                $gamePlayer->setPlace($scoresAndPlace[$playerId]['place']);
                $wonder = ($wonders[$playerKey]) ? $wonders[$playerKey] : null;
                $side = $sides[$playerKey] ? $sides[$playerKey] : null;
                $gamePlayer->setWonderId(($wonder) ? $wonder : null);
                $gamePlayer->setSide(($side) ? $side : null);
                $this->gamePlayerService->save($gamePlayer);
            }
            //save game categories
            foreach ($this->getGameCategories($excludedCategoryIds) as $category) {
                $gameCategory = $this->gameCategoryFactory->create();
                $gameCategory->setGame($game);
                $gameCategory->setCategory($category);
                $this->gameCategoryService->save($gameCategory);

            }
            //save score
            foreach ($scoreData as $playerKey => $categories) {
                foreach ($categories as $categoryId => $score) {
                    $scoreItem = $this->scoreFactory->create();
                    $scoreItem->setPlayerId($playerIds[$playerKey]);
                    $scoreItem->setGame($game);
                    $scoreItem->setCategoryId($categoryId);
                    $scoreItem->setValue($score);
                    $this->scoreService->save($scoreItem);
                    $scoreItem->save();
                }
            }

            $this->transaction->commit();
            return $game;
        } catch (\Exception $e) {
            $this->transaction->rollback();
            throw $e;
        }

    }

    /**
     * @param $players
     * @param $scores
     * @return array
     */
    private function determineScoreAndPlace($players, $scores)
    {
        $scoreTotal = array_map(
            function ($item) {
                return array_sum($item);
            },
            $scores
        );
        arsort($scoreTotal);
        $place = 1;
        $index = 1;
        $playerScores = [];
        $lastScore = null;
        foreach ($scoreTotal as $key => $value) {
            if ($value != $lastScore) {
                $place = $index;
            }
            $playerScores[$players[$key]] = [
                'score' => $value,
                'place' => $place
            ];
            $lastScore = $value;
            $index++;
        }
        return $playerScores;
    }

    /**
     * @param array $exclude
     * @return mixed
     */
    private function getGameCategories(array $exclude)
    {
        $filter = [];
        if (count($exclude)) {
            $filter = [
                'Id' => [
                    $exclude,
                    Criteria::NOT_IN
                ]
            ];
        }
        $categories = $this->categoryService->getCategories($filter);
        return $categories;
    }
}
