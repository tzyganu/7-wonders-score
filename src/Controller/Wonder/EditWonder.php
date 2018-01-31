<?php
namespace Controller\Wonder;

use Controller\AuthInterface;
use Controller\ControllerInterface;
use Model\Factory\WonderFactory;
use Model\FlashMessage;
use Model\ResponseFactory;
use Service\Category;
use Service\Wonder;
use Symfony\Component\HttpFoundation\Request;

class EditWonder implements AuthInterface, ControllerInterface
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Category
     */
    private $wonderService;
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;
    /**
     * @var FlashMessage
     */
    private $flashMessage;
    /**
     * @var array
     */
    private $selectedMenu;
    /**
     * @var string
     */
    private $template;

    private $wonderFactory;

    /**
     * EditWonder constructor.
     * @param Request $request
     * @param \Twig_Environment $twig
     * @param Wonder $wonderService
     * @param WonderFactory $wonderFactory
     * @param ResponseFactory $responseFactory
     * @param FlashMessage $flashMessage
     * @param string $template
     * @param array $selectedMenu
     */
    public function __construct(
        Request $request,
        \Twig_Environment $twig,
        Wonder $wonderService,
        WonderFactory $wonderFactory,
        ResponseFactory $responseFactory,
        FlashMessage $flashMessage,
        $template = '',
        $selectedMenu = []
    ) {
        $this->request          = $request;
        $this->twig             = $twig;
        $this->wonderService    = $wonderService;
        $this->wonderFactory    = $wonderFactory;
        $this->responseFactory  = $responseFactory;
        $this->flashMessage     = $flashMessage;
        $this->template         = $template;
        $this->selectedMenu     = $selectedMenu;
    }

    /**
     * @return string|\Symfony\Component\HttpFoundation\Response
     */
    public function execute()
    {
        $id = $this->request->get('id');
        if ($id) {
            $wonder = $this->wonderService->getWonder($id);
            if (!$wonder) {
                $this->flashMessage->addErrorMessage("The score wonder does not exit");
                return $this->responseFactory->create(
                    ResponseFactory::REDIRECT,
                    [
                        'url' => $this->request->getBaseUrl().'/wonder/list'
                    ]
                );
            }
        } else {
            $wonder = $this->wonderFactory->create();
        }
        return $this->twig->render(
            $this->template,
            [
                'wonder' => $wonder,
                'selectedMenu' => $this->selectedMenu,
                'page_title' => $this->getPageTitle($wonder)
            ]
        );
    }

    /**
     * @param \Wonders\Wonder $wonder
     * @return string
     */
    private function getPageTitle(\Wonders\Wonder $wonder)
    {
        if ($wonder->getId()) {
            return "Edit Wonder: ".$wonder->getName();
        }
        return "New Wonder";
    }
}
