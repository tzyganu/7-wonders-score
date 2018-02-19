<?php
namespace Controller\Player;

use Controller\ControllerInterface;
use Model\Factory\PlayerFactory;
use Model\FlashMessage;

use Model\ResponseFactory;
use Model\UrlBuilder;
use Symfony\Component\HttpFoundation\Request;
use Wonders\Player;
use Wonders\PlayerQuery;

class ViewPlayer implements ControllerInterface
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
     * @var Player
     */
    private $player;

    private $playerStats;

    /**
     * ViewPlayer constructor.
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
        \Model\Stats\Player $playerStats,
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
        $this->playerStats      = $playerStats;
        $this->template         = $template;
        $this->selectedMenu     = $selectedMenu;
    }

    private function getPlayer()
    {
        if ($this->player === null) {
            $id = $this->request->get('id');
            $this->player = $this->playerService->getPlayer($id);
        }
        return $this->player;
    }

    /**
     * @return string
     */
    public function execute()
    {
        $player = $this->getPlayer();

        if (!$player) {
            $this->flashMessage->addErrorMessage("The player does not exit");
            return $this->responseFactory->create(
                ResponseFactory::REDIRECT,
                [
                    'url' => $this->urlBuilder->getUrl('player/list')
                ]
            );
        }
        return $this->twig->render(
            $this->template,
            [
                'player' => $player,
                'selectedMenu' => $this->selectedMenu,
                'page_title' => "View Player Stats: ".$player->getName(),
                'basic_stats' => $this->playerStats->getBasicStats($player),
                'category_basic' => array_values($this->playerStats->getBasicCategoryStats($player)),
                'wonder_basic' => $this->getBasicWonderStats(),
                'categories_rank' => $this->playerStats->getCategoryPerRank($player)
            ]
        );
    }

    private function getBasicWonderStats()
    {
        $player = $this->getPlayer();
        $data = $this->playerStats->getBasicWonderStats($player);
        usort($data, function ($elementA, $elementB) {
            return $elementA['y'] > $elementB['y'];
        });
        return array_values($data);
    }
}
