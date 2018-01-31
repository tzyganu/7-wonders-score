<?php
namespace Controller\Player;

use Controller\AuthInterface;
use Controller\ControllerInterface;
use Model\Factory\PlayerFactory;
use Model\FlashMessage;
use Model\ResponseFactory;
use Model\UrlBuilder;
use Symfony\Component\HttpFoundation\Request;

class SavePlayer implements AuthInterface, ControllerInterface
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;
    /**
     * @var \Service\Player
     */
    private $playerService;
    /**
     * @var FlashMessage
     */
    private $flashMessage;
    /**
     * @var PlayerFactory
     */
    private $playerFactory;
    /**
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * SavePlayer constructor.
     * @param Request $request
     * @param ResponseFactory $responseFactory
     * @param \Service\Player $playerService
     * @param PlayerFactory $playerFactory
     * @param UrlBuilder $urlBuilder
     * @param FlashMessage $flashMessage
     */
    public function __construct(
        Request $request,
        ResponseFactory $responseFactory,
        \Service\Player $playerService,
        PlayerFactory $playerFactory,
        UrlBuilder $urlBuilder,
        FlashMessage $flashMessage
    ) {
        $this->request          = $request;
        $this->responseFactory  = $responseFactory;
        $this->playerService    = $playerService;
        $this->playerFactory    = $playerFactory;
        $this->urlBuilder       = $urlBuilder;
        $this->flashMessage     = $flashMessage;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function execute()
    {
        $id = $this->request->get('id');
        try {
            if ($id) {
                $player = $this->playerService->getPlayer($id);
                if (!$player) {
                    throw new \Exception("Player with id {$id} does not exist");
                }
            } else {
                $player = $this->playerFactory->create();
            }
            $player->setName($this->request->get('name'));
            $this->playerService->save($player);
            $this->flashMessage->addSuccessMessage("The player was saved");
            return $this->responseFactory->create(
                ResponseFactory::REDIRECT,
                [
                    'url' => $this->urlBuilder->getUrl('player/list')
                ]
            );
        } catch (\Exception $e) {
            $this->flashMessage->addErrorMessage($e->getMessage());
            $url = ($id) ? 'player/edit' : 'player/new';
            $params = ($id) ? ['id' => $id] : [];
            return $this->responseFactory->create(
                ResponseFactory::REDIRECT,
                [
                    'url' => $this->urlBuilder->getUrl($url, $params)
                ]
            );
        }
    }
}
