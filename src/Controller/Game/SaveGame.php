<?php
namespace Controller\Game;

use Controller\AuthInterface;
use Controller\BaseController;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Propel;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Wonders\Game;
use Wonders\GameCategory;
use Wonders\GamePlayer;
use Wonders\Player;
use Wonders\Score;
use Wonders\User;

class SaveGame extends BaseController implements AuthInterface
{
    /**
     * @return RedirectResponse
     */
    public function execute()
    {
        $conn = Propel::getConnection();
        /** @var User $user */
        $user = $this->session->get('user');
        try {
            $conn->beginTransaction();
            $playerIds = $this->request->get('player');
            $newPlayers = $this->request->get('new_player');
            foreach ($playerIds as $key => $playerId) {
                if ($playerId == 0) {
                    //save new player
                    $player = new Player();
                    $player->setName($newPlayers[$key]);
                    $player->save();
                    $playerIds[$key] = $player->getId();
                }
            }
            //save game instance
            $game = new Game();
            $game->setDate($this->request->get('game_date'));
            $game->setUserId($user->getId());
            $game->save();
            //save game players
            $scoreData = $this->request->get('score');

            $scoresAndPlace = $this->determineScoreAndPlace($playerIds, $scoreData);
            $wonders = $this->request->get('wonder');
            $sides = $this->request->get('side');
            foreach ($playerIds as $playerKey => $playerId) {
                $gamePlayer = new GamePlayer();
                $gamePlayer->setGame($game);
                $gamePlayer->setPlayerId($playerId);
                $gamePlayer->setPoints($scoresAndPlace[$playerId]['score']);
                $gamePlayer->setPlace($scoresAndPlace[$playerId]['place']);
                $wonder = ($wonders[$playerKey]) ? $wonders[$playerKey] : null;
                $side = $sides[$playerKey] ? $sides[$playerKey] : null;
                $gamePlayer->setWonderId(($wonder) ? $wonder : null);
                $gamePlayer->setSide(($side) ? $side : null);
                $gamePlayer->save();
            }
            //save game categories
            foreach ($this->getGameCategories($this->request->get('skip_category', [])) as $category) {
                $gameCategory = new GameCategory();
                $gameCategory->setGame($game);
                $gameCategory->setCategory($category);
                $gameCategory->save();

            }
            //save score

            foreach ($scoreData as $playerKey => $categories) {
                foreach ($categories as $categoryId => $score) {
                    $scoreItem = new Score();
                    $scoreItem->setPlayerId($playerIds[$playerKey]);
                    $scoreItem->setGame($game);
                    $scoreItem->setCategoryId($categoryId);
                    $scoreItem->setValue($score);
                    $scoreItem->save();
                }
            }
            $conn->commit();
            $this->addFlashMessage(self::FLASH_MESSAGE_SUCCESS, 'Game was saved successfully');
        } catch (\Exception $e) {
            $this->addFlashMessage(self::FLASH_MESSAGE_ERROR, $e->getMessage());
            $conn->rollBack();
        }
        return new RedirectResponse($this->request->getBaseUrl().'/game/list');

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

    private function getGameCategories(array $exclude)
    {
        $categories = \Wonders\CategoryQuery::create();
        if (count($exclude)) {
            $categories->filterById($exclude, Criteria::NOT_IN);
        }
        return $categories;
    }
}
