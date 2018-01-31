<?php
namespace Controller\Game;

use Controller\ControllerInterface;
use Model\Factory;
use Model\FlashMessage;
use Model\GameManager;
use Model\ResponseFactory;
use Model\Transaction;
use Model\UrlBuilder;
use Propel\Runtime\ActiveQuery\Criteria;
use Service\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Wonders\Game;
use Wonders\GameCategory;
use Wonders\GamePlayer;
use Wonders\Player;
use Wonders\Score;
use Wonders\User;

class SaveGame implements ControllerInterface
{
    /**
     * @var Session
     */
    private $session;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var FlashMessage
     */
    private $flashMessage;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;
    /**
     * @var UrlBuilder
     */
    private $urlBuilder;
    /**
     * @var GameManager
     */
    private $gameManager;

    /**
     * SaveGame constructor.
     * @param Request $request
     * @param Session $session
     * @param FlashMessage $flashMessage
     * @param ResponseFactory $responseFactory
     * @param UrlBuilder $urlBuilder
     * @param GameManager $gameManager
     */
    public function __construct(
        Request $request,
        Session $session,
        FlashMessage $flashMessage,
        ResponseFactory $responseFactory,
        UrlBuilder $urlBuilder,
        GameManager $gameManager
    ) {
        $this->request          = $request;
        $this->session          = $session;
        $this->flashMessage     = $flashMessage;
        $this->responseFactory  = $responseFactory;
        $this->urlBuilder       = $urlBuilder;
        $this->gameManager      = $gameManager;
    }

    /**
     * @return Response
     */
    public function execute()
    {
        /** @var User $user */
        $user = $this->session->get('user');
        try {
            $playerIds = $this->request->get('player');
            $newPlayers = $this->request->get('new_player', []);
            $excludedCategoryIds = $this->request->get('skip_category', []);
            $wonders = $this->request->get('wonder');
            $sides = $this->request->get('side');
            $scoreData = $this->request->get('score');

            $gameData = [
                'game_date' => $this->request->get('game_date'),
                'user_id' => $user->getId()
            ];

            $game = $this->gameManager->saveGame(
                $gameData,
                $playerIds,
                $newPlayers,
                $scoreData,
                $wonders,
                $sides,
                $excludedCategoryIds
            );
            $this->flashMessage->addSuccessMessage("Game was saved successfully");
        } catch (\Exception $e) {
            $this->flashMessage->addErrorMessage($e->getMessage());
            return $this->responseFactory->create(
                ResponseFactory::REDIRECT,
                [
                    'url' => $this->urlBuilder->getUrl('game/new')
                ]
            );
        }
        return $this->responseFactory->create(
            ResponseFactory::REDIRECT,
            [
                'url' => $this->urlBuilder->getUrl('game/view', ['id' => $game->getId()])
            ]
        );
    }
}
