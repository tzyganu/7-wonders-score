<?php
namespace Controller\Player;

use Controller\AuthInterface;
use Controller\ControllerInterface;
use Model\Factory\PlayerFactory;
use Model\FlashMessage;
use Model\Query\PlayerQueryFactory;
use Model\ResponseFactory;
use Model\UrlBuilder;
use Symfony\Component\HttpFoundation\Request;
use Wonders\Player;
use Wonders\PlayerQuery;

class EditPlayer implements AuthInterface, ControllerInterface
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var \Service\Player
     */
    private $playerService;
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var string
     */
    private $selectedMenu;
    /**
     * @var string
     */
    private $template;
    /**
     * @var string
     */
    private $pageTitle;
    /**
     * @var PlayerFactory
     */
    private $playerFactory;
    /**
     * @var FlashMessage
     */
    private $flashMessage;
    /**
     * @var UrlBuilder
     */
    private $urlBuilder;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * EditPlayer constructor.
     * @param Request $request
     * @param PlayerFactory $playerFactory
     * @param \Service\Player $playerService
     * @param \Twig_Environment $twig
     * @param ResponseFactory $responseFactory
     * @param FlashMessage $flashMessage
     * @param UrlBuilder $urlBuilder
     * @param string $template
     * @param array $selectedMenu
     */
    public function __construct(
        Request $request,
        PlayerFactory $playerFactory,
        \Service\Player $playerService,
        \Twig_Environment $twig,
        ResponseFactory $responseFactory,
        FlashMessage $flashMessage,
        UrlBuilder $urlBuilder,
        $template = '',
        $selectedMenu = []
    ) {
        $this->request          = $request;
        $this->playerFactory    = $playerFactory;
        $this->playerService    = $playerService;
        $this->twig             = $twig;
        $this->responseFactory  = $responseFactory;
        $this->flashMessage     = $flashMessage;
        $this->urlBuilder       = $urlBuilder;
        $this->template         = $template;
        $this->selectedMenu     = $selectedMenu;
    }

    /**
     * @return string
     */
    public function execute()
    {
        $id = $this->request->get('id');
        $player = $this->playerFactory->create();
        if ($id) {
            $player = $this->playerService->getPlayer($id);
            if (!$player) {
                $this->flashMessage->addErrorMessage("The player does not exit");
                return $this->responseFactory->create(
                    ResponseFactory::REDIRECT,
                    [
                        'url' => $this->urlBuilder->getUrl('player/list')
                    ]
                );
            }
        }
        return $this->twig->render(
            $this->template,
            [
                'player' => $player,
                'selectedMenu' => $this->selectedMenu,
                'page_title' => $this->getPageTitle($player)
            ]
        );
    }

    /**
     * @param Player $player
     * @return string
     */
    public function getPageTitle(Player $player)
    {
        if ($player->getId()) {
            return 'Edit Player: '.$player->getName();
        }
        return 'Add new player';
    }
}
